<?php

namespace App\Http\Controllers\Admin;

use Config;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Mail\UserRegisterMail;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Repositories\ParentRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\Requests\UserRequest;
use App\Http\Controllers\Admin\Requests\PaymentMethodRequest;

class UserController extends Controller {

    protected $repository;
    private $stripeCustomer;
    private $stripe_customer_id;

    public function __construct(ParentRepository $repository) {
        $this->moduleName = "Users";
        $this->moduleRoute = url('admin/users');
        $this->moduleView = "admin.user";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);

        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view($this->moduleView . ".index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $phone_codes = $this->repository->CountryRepository()->getCountryCodeForSelectBox();
        $countries = $this->repository->CountryRepository()->getCountryForSelectBox();
        return view($this->moduleView . '.create', compact('phone_codes', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request) {
        $input = $request->except('_token');
        try {
            $validateUserPhoneNumber = $this->repository->UserRepository()->checkPhoneNumberUnique($input);
            $validateUserEthiopiaPhoneNumber = $this->repository->UserRepository()->checkEthiopiaPhoneNumberUnique($input);

            if ($validateUserPhoneNumber['status'] == 'false') {
                return back()->withErrors("phone_number", $validateUserPhoneNumber['message']);
            }

            if ($validateUserEthiopiaPhoneNumber['status'] == 'false') {
                return back()->withErrors("ethiopia_phone_number", $validateUserPhoneNumber['message']);
            }

            $input['password'] = Hash::make($input['password']);

            if (isset($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['name'] != "") {
                $input['profile_picture'] = $this->repository->UserRepository()->uploadImage($request->file('profile_picture'));
            } else {
                unset($input['profile_picture']);
            }

            $result = $this->repository->UserRepository()->store($input);

            if ($result) {
                $address_data = [
                    "address_line_1" => $request->address_line_1,
                    "city_id" => $request->city_id,
                    "state_id" => $request->state_id,
                    "country_id" => $request->country_id,
                    "zipcode" => $request->zipcode,
                    "user_id" => $result->id,
                ];

                $this->repository->addressRepository()->store($address_data);

                $emailQueueMessage = (new UserRegisterMail($result))->onQueue('emails');
                Mail::to($result->email, $result->fullname)->queue($emailQueueMessage);

                addToLog('User was added succesfully.', 'users', json_encode($result));
        
                $request->session()->flash('success', "User was added succesfully.");
            } else {
                $request->session()->flash('error', "User was not added.");
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect($this->moduleRoute);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $user = $this->repository->UserRepository()->getTable()
                ->select(['users.id',
                    'users.firstname',
                    'users.lastname',
                    'users.email as email',
                    'users.profile_picture',
                    DB::raw("DATE_FORMAT(CONVERT_TZ(TIMESTAMP(users.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'), '%M %Y') AS joindtDisplay"),
                    DB::raw('concat(users.firstname," ",users.lastname) as fullname'),
                    DB::raw('concat(users.phone_code,"",users.phone_number) as phone_number'),
                    DB::raw('concat(users.ethiopia_phone_code,users.ethiopia_phone_number) as ethiopia_phone_number'),
                    'users.federal_tax_id',
                    'users.created_at as date',
                    DB::raw("CONCAT('$', COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0)) AS transactionsAmountSum"),
                    DB::raw("FORMAT(COUNT(transactions.id), 0) AS transactionsCountDisplay"),
                ])
                ->leftJoin('transactions', function ($join) {
                    $join->on('transactions.user_id', '=', 'users.id');
                })
                ->where('users.id', '=', $id)
                ->groupBy('users.id')
                ->first();

        $payments = PaymentMethod::select('payment_methods.card_number', 'payment_methods.method_type', 'payment_methods.paypal_email', 'transactions.total_pay_amount', DB::raw("CONCAT('$', COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0)) AS transactionsAmountSum"))
                ->where('payment_methods.user_id', '=', $id)
                ->leftJoin('transactions', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->groupBy('payment_methods.id')
                ->get();

        $usersPayment = Transaction::select(DB::raw('COUNT(*) as totalTransaction'),
                    DB::raw('concat(users.firstname," ",users.lastname) as userFullname'))
                    ->join('users', 'users.id', '=', 'transactions.user_id')        
                    ->groupBy('user_id')->get();
        // $transactions = Transaction::select('payment_methods.card_number',
        // 'payment_methods.method_type', 'payment_methods.paypal_email', 'transactions.total_pay_amount',
        // DB::raw("CONCAT('$', COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0)) AS transactionsAmountSum"))
        //     ->where('transactions.user_id', '=', $id)
        //     ->rightJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
        //     ->groupBy('payment_methods.id')
        //     ->groupBy('payment_methods.paypal_email')
        //     ->get();
        // return $transactions;
        return view('admin.user.viewDetail', compact('user', 'payments', 'usersPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request) {
        try {
            $user = $this->repository->UserRepository()->getById($id);
            if ($user) {
                $phone_codes = $this->repository->CountryRepository()->getCountryCodeForSelectBox();
                $countries = $this->repository->CountryRepository()->getCountryForSelectBox();
                $address = $this->repository->addressRepository()->getTable()
                        ->where('user_id', '=', $id)
                        ->select('address_line_1', 'city_id', 'state_id', 'country_id', 'zipcode');

                if (isset($address) && $address->count()) {
                    $address = $address->first();
                    $user->address_line_1 = $address->address_line_1;
                    $user->city_id = $address->city_id;
                    $user->state_id = $address->state_id;
                    $user->country_id = $address->country_id;
                    $user->zipcode = $address->zipcode;
                }

                return View::make($this->moduleView . ".edit", compact('countries', 'phone_codes', 'user', 'address'));
            }

            $request->session()->flash('error', "Something went wrong Please try again.");
            return redirect($this->moduleRoute);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id) {
        try {
            $inputs = $request->except('_token', 'address_line_1', 'city_id', 'state_id', 'country_id', 'zipcode');

            $data = $inputs;
            $data['user_id'] = $id;
            $validateUserPhoneNumber = $this->repository->UserRepository()->checkPhoneNumberUnique($data);
            $validateUserEthiopiaPhoneNumber = $this->repository->UserRepository()->checkEthiopiaPhoneNumberUnique($data);

            if ($validateUserPhoneNumber['status'] == 'false') {
                return back()->withErrors("phone_number", $validateUserPhoneNumber['message']);
            }

            if ($validateUserEthiopiaPhoneNumber['status'] == 'false') {
                return back()->withErrors("ethiopia_phone_number", $validateUserPhoneNumber['message']);
            }

            if (isset($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['name'] != "") {
                $user = $this->repository->UserRepository()->getById($id);

                $inputs['profile_picture'] = $this->repository->UserRepository()->uploadImage($request->file('profile_picture'));
                if ($inputs['profile_picture'] != "" && isset($user->profile_picture)) {
                    $USER_PROFILE_ORIGINAL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH');
                    $USER_PROFILE_SMALL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH');
                    $USER_PROFILE_MEDIUM_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH');
                    $USER_PROFILE_LARGE_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_LARGE_DOC_PATH');

                    if (File::exists($USER_PROFILE_ORIGINAL_DOC_PATH . '' . $user->profile_picture)) {
                        File::delete($USER_PROFILE_ORIGINAL_DOC_PATH . '' . $user->profile_picture);
                    }

                    if (File::exists($USER_PROFILE_SMALL_DOC_PATH . '' . $user->profile_picture)) {
                        File::delete($USER_PROFILE_SMALL_DOC_PATH . '' . $user->profile_picture);
                    }

                    if (File::exists($USER_PROFILE_MEDIUM_DOC_PATH . '' . $user->profile_picture)) {
                        File::delete($USER_PROFILE_MEDIUM_DOC_PATH . '' . $user->profile_picture);
                    }

                    if (File::exists($USER_PROFILE_LARGE_DOC_PATH . '' . $user->profile_picture)) {
                        File::delete($USER_PROFILE_LARGE_DOC_PATH . '' . $user->profile_picture);
                    }
                }
            } else {
                unset($inputs['profile_picture']);
            }
            if (isset($inputs['password']) && $inputs['password'] != "") {
                $inputs['password'] = Hash::make($inputs['password']);
            } else {
                unset($inputs['password']);
            }

            $isUpdated = $this->repository->UserRepository()->update($id, $inputs);
            
            if ($isUpdated) {

                $address_data = [
                    "address_line_1" => $request->address_line_1,
                    "city_id" => $request->city_id,
                    "state_id" => $request->state_id,
                    "country_id" => $request->country_id,
                    "zipcode" => $request->zipcode,
                    "user_id" => $id,
                ];

                $address = $this->repository->addressRepository()->getTable()->where('user_id', '=', $id);

                if (isset($address) && $address->count() > 0) {
                    $address_id = $address->first()->id;
                    $this->repository->addressRepository()->update($address_id, $address_data);
                } else {
                    $this->repository->addressRepository()->store($address_data);
                }

                addToLog('User was updated succesfully.', 'users', json_encode(User::where('id', $id)->first()));        
                
                $request->session()->flash('success', "User was updated successfully.");
                return redirect($this->moduleRoute);
            } else {
                $request->session()->flash('error', "User was not updated successfully.");
                return redirect($this->moduleRoute);
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return redirect($this->moduleRoute);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        $image_path = [
            config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH'),
            config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH'),
            config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH'),
            config('ethiopay.DOC_PATH.USER_PROFILE_LARGE_DOC_PATH'),
        ];

        return $this->repository->UserRepository()->destroy($id, $request, $image_path, 'profile_picture');
    }

    public function getDatatable(Request $request) {
        $users = User::select([
                    'users.*',
                    DB::raw("CONCAT(users.firstname, ' ', users.lastname) as fullname"),
                    DB::raw("CONCAT(users.phone_code, '', users.phone_number) as phone_number_full"),
                    DB::raw("COUNT(transactions.id) AS transactionsCount"),
                    DB::raw("FORMAT(COUNT(transactions.id), 0) AS transactionsCountDisplay"),
                    DB::raw("IF(SUM(transactions.total_pay_amount) IS NULL, 0, SUM(transactions.total_pay_amount)) AS transactionsAmountSum"),
                    DB::raw("CONCAT('$', FORMAT(IF(SUM(transactions.total_pay_amount) IS NULL, 0, SUM(transactions.total_pay_amount)) , 2)) AS transactionsAmountSumDisplay"),
                    DB::raw("DATE_FORMAT(CONVERT_TZ(TIMESTAMP(users.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'), '%Y-%m-%d %H:%i') AS joindtDisplay"),
                ])->leftJoin('transactions', function ($join) {
                    $join->on('transactions.user_id', '=', 'users.id');
                })
                ->groupBy('users.id');

        if ($keyword = $request->get('search')['value']) {
            $users->having("id", 'like', "%$keyword%");
            $users->orHaving("fullname", 'like', "%$keyword%");
            $users->orHaving("email", 'like', "%$keyword%");
            $users->orHaving('transactionsCountDisplay', 'LIKE', "%$keyword%");
            $users->orHaving('transactionsAmountSumDisplay', 'LIKE', "%$keyword%");
        }

        $datatables = Datatables::of($users);
        $datatables->filterColumn('fullname', function ($query, $keyword) {
            
        })->filterColumn('transactionsCount', function ($query, $keyword) {
            
        })->filterColumn('transactionsAmountSum', function ($query, $keyword) {
            
        });

        return $datatables->make(true);
    }

    public function checkEmailUnique(Request $request) {
        $inputs = $request->all();
        return $this->repository->UserRepository()->checkEmailUnique($inputs);
    }

    public function checkPhoneNumberUnique(Request $request) {
        $inputs = $request->all();
        $data = $this->repository->UserRepository()->checkPhoneNumberUnique($inputs);
        return Response::json($data);
    }

    public function checkEthiopiaPhoneNumberUnique(Request $request) {
        $inputs = $request->all();
        $data = $this->repository->UserRepository()->checkEthiopiaPhoneNumberUnique($inputs);
        return Response::json($data);
    }

    public function restore($id) {
        return $this->repository->UserRepository()->restore($id);
    }

    public function transactionChartData(Request $request, $chartType, $id) {
        $currentDate = Carbon::now();
//        $currentDate = Carbon::parse('2017-11-16', session('ADMIN_TIMEZONE_STR'));

        $totalTransactionChartDataSet = [];

        $graphs = Transaction::select('service_types.service_name', 'transactions.total_pay_amount as total_amount', DB::raw('GROUP_CONCAT(transactions.total_pay_amount) as amount'))->where('transactions.user_id', '=', $id)
                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                ->groupBy('service_providers.service_type_id')
                ->get();

        if (count($graphs) == 0) {
            $transactionChartLabels = [];
            $transactionChartDataSet = [];

            if ($chartType == 'day') {
                $transactionDate = $currentDate->startOfDay()->format('Y-m-d H:i:s');

                $hoursInDay = [];
                for ($i = 0; $i <= 24; $i++) {
                    $hoursInDay[] = ($i < 10) ? $i : $i;
                }
                foreach ($hoursInDay as $key => $hour) {
                    $transactionChartDataSet[] = 0;
                    $transactionChartLabels[] = ($hour < 10) ? '0' . (string) $hour : (string) $hour;
                }
            } else if ($chartType == 'week') {
                $transactionDate = $currentDate->startOfWeek()->format('Y-m-d H:i:s');

                $transactionChartLabels = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];

                foreach ($transactionChartLabels as $key => $weekDay) {
                    $transactionChartDataSet[] = 0;
                }
            } else if ($chartType == 'month') {
                $transactionDate = $currentDate->startOfMonth()->format('Y-m-d H:i:s');

                $daysInMonthArr = [];
                for ($i = 1; $i <= $currentDate->daysInMonth; $i++) {
                    $daysInMonthArr[] = ($i < 10) ? $i : $i;
                }
                foreach ($daysInMonthArr as $key => $dateDay) {
                    $transactionChartDataSet[] = 0;
                    $transactionChartLabels[] = ($dateDay < 10) ? '0' . (string) $dateDay : (string) $dateDay;
                }
            } else if ($chartType == 'year') {
                $transactionChartLabels = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
                foreach ($transactionChartLabels as $key => $monthName) {
                    $transactionChartDataSet[] = 0;
                }
            }
        }

        foreach ($graphs as $graph) {
            $transactionChartLabels = [];
            $transactionChartDataSet = [];

            if ($chartType == 'day') {
                $transactionDate = $currentDate->startOfDay()->format('Y-m-d H:i:s');

                $transactionChartRes = Transaction::select('service_types.service_name', DB::raw('sum(transactions.total_pay_amount) as total_amount'), DB::raw("HOUR(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')) dateHour"))
                                ->where('transactions.user_id', '=', $id)
                                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                                ->where('service_types.service_name', $graph->service_name)
                                ->where(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $transactionDate)
                                ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                                ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                                ->get()->toArray();

                $transactionChartResDayDate = array_column($transactionChartRes, 'dateHour');
                $transactionChartLabels = [];
                $transactionChartDataSet = [];

                $hoursInDay = [];
                for ($i = 0; $i <= 24; $i++) {
                    $hoursInDay[] = ($i < 10) ? $i : $i;
                }

                $transactionChartResIndex = 0;
                foreach ($hoursInDay as $key => $hour) {
                    if (in_array($hour, $transactionChartResDayDate)) {
                        $transactionChartDataSet[] = $transactionChartRes[$transactionChartResIndex]['total_amount'];
                        $transactionChartLabels[] = ($hour < 10) ? '0' . (string) $hour : (string) $hour;
                        $transactionChartResIndex++;
                    } else {
                        $transactionChartDataSet[] = 0;
                        $transactionChartLabels[] = ($hour < 10) ? '0' . (string) $hour : (string) $hour;
                    }
                }
            } else if ($chartType == 'week') {
                $transactionDate = $currentDate->startOfWeek()->format('Y-m-d H:i:s');
                $transactionChartRes = Transaction::select('service_types.service_name', DB::raw('sum(transactions.total_pay_amount) as total_amount'), DB::raw("UCASE(DAYNAME(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))) dayName"))->where('transactions.user_id', '=', $id)
                                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                                ->where(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $transactionDate)
                                ->where('service_types.service_name', $graph->service_name)
                                ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                                ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                                ->get()->toArray();

                $transactionChartResDayName = array_column($transactionChartRes, 'dayName');
                $transactionChartLabels = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];

                $transactionChartResIndex = 0;
                foreach ($transactionChartLabels as $key => $weekDay) {
                    if (in_array($weekDay, $transactionChartResDayName)) {
                        $transactionChartDataSet[] = $transactionChartRes[$transactionChartResIndex]['total_amount'];
                        $transactionChartResIndex++;
                    } else {
                        $transactionChartDataSet[] = 0;
                    }
                }
            } else if ($chartType == 'month') {
                $transactionDate = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
                $transactionChartRes = Transaction::select('service_types.service_name', DB::raw('sum(transactions.total_pay_amount) as total_amount'), DB::raw("DAY(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')) dateDay"))
                                ->where('transactions.user_id', '=', $id)
                                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                                ->where(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $transactionDate)
                                ->where('service_types.service_name', $graph->service_name)
                                ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                                ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                                ->get()->toArray();

                $transactionChartResDayDate = array_column($transactionChartRes, 'dateDay');
                $transactionChartLabels = [];
                $transactionChartDataSet = [];

                $daysInMonthArr = [];
                for ($i = 1; $i <= $currentDate->daysInMonth; $i++) {
                    $daysInMonthArr[] = ($i < 10) ? $i : $i;
                }

                $transactionChartResIndex = 0;
                foreach ($daysInMonthArr as $key => $dateDay) {
                    if (in_array($dateDay, $transactionChartResDayDate)) {
                        $transactionChartDataSet[] = $transactionChartRes[$transactionChartResIndex]['total_amount'];
                        $transactionChartLabels[] = ($dateDay < 10) ? '0' . (string) $dateDay : (string) $dateDay;
                        $transactionChartResIndex++;
                    } else {
                        $transactionChartDataSet[] = 0;
                        $transactionChartLabels[] = ($dateDay < 10) ? '0' . (string) $dateDay : (string) $dateDay;
                    }
                }
            } else if ($chartType == 'year') {

                $transactionDate = $currentDate->startOfYear()->format('Y-m-d H:i:s');
                $transactionChartRes = Transaction::select('service_types.service_name', DB::raw('sum(transactions.total_pay_amount) as total_amount'), DB::raw("UCASE(MONTHNAME(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))) monthName"))
                                ->where('transactions.user_id', '=', $id)
                                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                                ->where(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $transactionDate)
                                ->where('service_types.service_name', $graph->service_name)
                                ->groupBy(DB::raw("MONTHNAME(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                                ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                                ->get()->toArray();

                $transactionChartResMonthName = array_column($transactionChartRes, 'monthName');
                $transactionChartLabels = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
                $transactionChartResIndex = 0;
                foreach ($transactionChartLabels as $key => $monthName) {
                    if (in_array($monthName, $transactionChartResMonthName)) {
                        $transactionChartDataSet[] = $transactionChartRes[$transactionChartResIndex]['total_amount'];
                        $transactionChartResIndex++;
                    } else {
                        $transactionChartDataSet[] = 0;
                    }
                }
            }
            $totalTransactionChartDataSet[] = $transactionChartDataSet;
        }

        $borderColor = ["rgba(0, 146, 69, 1)", "rgba(147, 197, 22, 1)", "rgba(22, 197, 94, 1)",
            "rgba(255, 0, 0, 1)", "rgba(0, 0, 255, 1)", "rgba(255, 99, 71, 1)",
            "rgba(100, 43, 162, 1)", "rgba(248, 43, 123, 1)", "rgba(248, 129, 149, 1)",
            "rgba(111, 129, 149, 1)", "rgba(111, 129, 47, 1)", "rgba(60, 28, 16, 1)",
            "rgba(60, 134, 16, 1)", "rgba(0, 255, 255, 1)", "rgba(255, 255, 0, 1)",
            "rgba(255, 150, 211, 1)", "rgba(255, 150, 64, 1)", "rgba(196, 150, 191, 1)",
            "rgba(196, 150, 93, 1)", "rgba(84, 150, 204, 1)", "rgba(84, 17, 204, 1)",
        ];

        $colorArrayCount = count($borderColor);

        for ($i = 0; $i < count($totalTransactionChartDataSet) - $colorArrayCount; $i++) {
            $borderColor[] = "rgba(0, 0, 255, 1)";
        }

        $totalDataset = [];

        for ($i = 0; $i < count($totalTransactionChartDataSet); $i++) {
            $dataset = [];
            $dataset = [
                "data" => $totalTransactionChartDataSet[$i],
                "backgroundColor" => $borderColor[$i],
                "borderColor" => $borderColor[$i],
                "borderWidth" => 2,
                "pointRadius" => 0,
                "lineTension" => 0,
                "fill" => false,
            ];
            $totalDataset[] = $dataset;
        }

        return response()->json(['transactionChartLabels' => $transactionChartLabels, 'transactionChartDataSet' => $totalTransactionChartDataSet, 'totalDataSet' => $totalDataset, 'serviceName' => $graphs, 'color' => $borderColor]);
    }

    public function transactionsList($id, Request $request) {
        $user = User::find($id);
        return view('admin.user.viewDetailSections.transaction.index', compact('user'));
    }

    public function transactionsDatatable($id, Request $request) {
        $transactions = Transaction::
                select('transactions.*', 'users.firstname', 'users.lastname', 'service_providers.provider_name', DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'), DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname'), DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M") AS date'), DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M / %Y") AS fromToDate'), DB::raw('IF(transactions.transaction_status = "failed", "FAIL", "COMPLETE") as status'), DB::raw('CONCAT("$", FORMAT(transactions.total_pay_amount, 2)) as amount'))
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->where('transactions.user_id', $id);

            if(($startingDate = $request->get('startingDate')) != ''){
                $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $startingDate);
            }
    
            if(($endDate = $request->get('endDate')) != ''){
                $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $endDate);
            }
        // if (($from_date = trim($request->get('from_date'))) != '') {
        //     $from_date_format = Carbon::createFromFormat('d / M / Y', $from_date)->format('Y-m-d');
        //     $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $from_date_format);
        // }

        // if (($to_date = $request->get('to_date')) != '') {
        //     $to_date_format = Carbon::createFromFormat('d / M / Y', $to_date)->format('Y-m-d');
        //     $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $to_date_format);
        // }

        return Datatables::of($transactions)
                        ->filterColumn('created_at', function ($query, $keyword) {
                            $sql = 'DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M")  like ?';
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                            // $query->having("date", 'like', "%$keyword%");
                        })
                        ->filterColumn('provider_name', function ($query, $keyword) {
                            $query->whereRaw("service_providers.provider_name like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('amount', function ($query, $keyword) {
                            $sql = 'CONCAT("$", FORMAT(transactions.total_pay_amount, 2)) like ?';
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->filterColumn('fullname', function ($query, $keyword) {
                            $sql = "CONCAT(users.firstname,' ',users.lastname)  like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                            // $query->having("fullname like ?", ["%{$keyword}%"]);
                        })
                        ->filterColumn('debtorFullname', function ($query, $keyword) {
                            $sql = "CONCAT(transactions.debtor_firstname,' ',transactions.debtor_lastname) like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->filterColumn('status', function ($query, $keyword) {
                            $query->whereRaw("IF(transactions.transaction_status = 'failed', 'FAIL', 'COMPLETE') like ?", ["%{$keyword}%"]);
                        })
                        ->make(true);
    }

    public function transactionsDetail($id, $transactionid, Request $request) {
        $user = User::find($id);
        $transaction = Transaction::select("transactions.*", 'service_providers.provider_name', 'service_types.service_name')
                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->join('service_types', 'service_providers.service_type_id', '=', 'service_types.id')
                ->where('transactions.id', $transactionid)
                ->where('transactions.user_id', $id)
                ->first();

        if ($transaction) {
            $payment = PaymentMethod::where('id', $transaction->payment_method_id)->first();

            return view($this->moduleView . '.viewDetailSections.transaction.show', compact('transaction', 'payment', 'user'));
        }
        $request->session()->flash('error', "Transaction Not Found.");
        return redirect($this->moduleRoute . '/' . $user->id . '/transactions');
    }

    public function paymentMethodsList($id, Request $request) {
        $user = User::find($id);
        return view($this->moduleView . '.viewDetailSections.paymentMethod.index', compact('user'));
    }

    public function paymentMethodsDatatable($id, Request $request) {
        $user = User::find($id);
        $payments = PaymentMethod::select([
                    'payment_methods.id', 'payment_methods.card_number',
                    DB::raw("CONCAT(payment_methods.card_number, '(', payment_methods.card_type, ')') AS cardTypeNumber"),
                    'payment_methods.method_type', 'payment_methods.paypal_email', 'transactions.total_pay_amount',
                    DB::raw("COALESCE(SUM(transactions.total_pay_amount), 0) AS transactionsAmountSum"),
                    DB::raw("CONCAT('$', COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0)) AS transactionsAmountSumDisplay"),
                ])
                ->where('payment_methods.user_id', '=', $id)
                ->leftJoin('transactions', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->groupBy('payment_methods.id');

        return Datatables::of($payments)
                        ->editColumn('method_type', function ($payments) {
                            return ucfirst($payments->method_type);
                        })
                        ->filterColumn('cardTypeNumber', function ($query, $keyword) {
                            $sql = "CONCAT(payment_methods.card_number, '(', payment_methods.card_type, ')')  like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        // ->filterColumn('transactionsAmountSum', function ($query, $keyword) {
                        //     $sql = "CONCAT('$', COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0))  like ?";
                        //     $query->whereRaw($sql, ["%{$keyword}%"]);
                        // })
                        ->make(true);
    }

    public function paymentMethodsCreate($id, Request $request) {
        $user = User::find($id);
        return view($this->moduleView . '.viewDetailSections.paymentMethod.create', compact('user'));
    }

    public function paymentMethodsStore($id, PaymentMethodRequest $request) {
        $input = $request->all();
        try {
            $user_id = $id;
            $user = User::find($id);

            if ($input['paymentMethodType'] == 'card') {
                $this->stripe_customer_id = $user->stripe_customer_id;
                $this->stripeCustomer = \Stripe\Customer::retrieve($this->stripe_customer_id);

                $customerChargeRefundJson = '';
                $tokenRetrieveRes = \Stripe\Token::retrieve($input['stripe_token']);
                //dd($tokenRetrieveRes);
                if (!$tokenRetrieveRes) {
                    $data = array(
                        'status' => false,
                        'message' => 'Something went wrong with your card.',
                    );
                    return response()->json($data);
                }

                $fingerprint = $tokenRetrieveRes->card->fingerprint;

                $isCardAlreadyExists = PaymentMethod::where('user_id', $user_id)->where('stripe_card_fingerprint', $fingerprint)->exists();
                if ($isCardAlreadyExists) {
                    $data = array(
                        'status' => false,
                        'message' => 'Card already exists.',
                    );
                    return response()->json($data);
                }
                try {
                    // Create Card
                    $customerCardTokenId = $input['stripe_token'];
                    $customerCard = $this->stripeCustomer->sources->create(array("source" => $customerCardTokenId));
                    $customerCardJson = json_encode($customerCard);

                    $customerCardChargeRes = $this->createCardValidationCharge($customerCard, $user_id);
                    if ($customerCardChargeRes['status'] == true) {
                        $customerCardCharge = $customerCardChargeRes['customerCardCharge'];
                        $customerCardChargeJson = json_encode($customerCardCharge);

                        $customerChargeRefundRes = $this->refundCardValidationCharge($customerCardCharge);
                        if ($customerChargeRefundRes['status'] == true) {
                            $customerChargeRefund = $customerChargeRefundRes['customerChargeRefund'];
                            $customerChargeRefundJson = json_encode($customerChargeRefund);
                        }

                        $paymentMethod = new PaymentMethod();
                        $paymentMethod->user_id = $user_id;
                        $paymentMethod->method_type = 'card';
                        $paymentMethod->card_type = $customerCard->brand;
                        $paymentMethod->name_on_card = $customerCard->name;
                        $paymentMethod->card_number = $customerCard->last4;
                        $paymentMethod->card_expiry_month = $customerCard->exp_month;
                        $paymentMethod->card_expiry_year = $customerCard->exp_year;
                        $paymentMethod->stripe_card_id = $customerCard->id;
                        $paymentMethod->stripe_card_fingerprint = $customerCard->fingerprint;
                        $paymentMethod->save();
                    } else {
                        $this->stripeCustomer->sources->retrieve($customerCard->id)->delete();

                        $data = array(
                            'status' => false,
                            'message' => 'insufficient funds in your account.',
                            'customerCardChargeRes' => $customerCardChargeRes,
                        );
                        return response()->json($data);
                    }
                } catch (\Stripe\Error\RateLimit $e) {
                    // Too many requests made to the API too quickly
                    $stripeResponseBody = $e->getJsonBody();
                    $stripeResponseBodyErr = $stripeResponseBody['error'];
                    Log::info($stripeResponseBodyErr);

                    $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                    return response()->json($data);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    // Invalid parameters were supplied to Stripe's API
                    $stripeResponseBody = $e->getJsonBody();
                    $stripeResponseBodyErr = $stripeResponseBody['error'];
                    Log::info($stripeResponseBodyErr);

                    $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                    return response()->json($data);
                } catch (\Stripe\Error\Authentication $e) {
                    // Authentication with Stripe's API failed
                    // (maybe you changed API keys recently)
                    $stripeResponseBody = $e->getJsonBody();
                    $stripeResponseBodyErr = $stripeResponseBody['error'];
                    Log::info($stripeResponseBodyErr);

                    $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                    return response()->json($data);
                } catch (\Stripe\Error\ApiConnection $e) {
                    // Network communication with Stripe failed
                    $stripeResponseBody = $e->getJsonBody();
                    $stripeResponseBodyErr = $stripeResponseBody['error'];
                    Log::info($stripeResponseBodyErr);

                    $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                    return response()->json($data);
                } catch (\Stripe\Error\Base $e) {
                    $stripeResponseBody = $e->getJsonBody();
                    $stripeResponseBodyErr = $stripeResponseBody['error'];
                    Log::info($stripeResponseBodyErr);

                    $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                    return response()->json($data);
                } catch (Exception $e) {
                    $stripeResponseBody = $e->getMessage();
                    $data = array('status' => false, 'message' => $stripeResponseBody);
                    return response()->json($data);
                }
                $request->session()->flash('success', 'Payment method added successfully.');
                $data = array('status' => true, 'message' => 'Payment method added successfully.');
                return response()->json($data);
            } else {
                $data = array(
                    'status' => false,
                    'message' => 'Something went wrong with your payment method.',
                );
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return redirect($this->moduleRoute . '/' . $user->id . '/create');
        }
    }

    public function createCardValidationCharge($customerCard, $user_id) {
        try {            
            // Charge Customer
            $customerCardId = $customerCard->id;
            $customerCardCharge = \Stripe\Charge::create(array(
                        "amount" => 0.5 * 100,
                        "currency" => "usd",
                        "customer" => $this->stripe_customer_id,
                        "source" => $customerCardId,
                        "description" => "Charge for validate card for customer id : {$user_id}",
            ));
            
            if ($customerCardCharge->status == 'succeeded') {
                $data = array(
                    'status' => true,
                    'message' => 'card charge created successfully',
                    'customerCardCharge' => $customerCardCharge,
                );
                return $data;
            } else {
                $data = array(
                    'status' => false,
                    'message' => 'insufficient funds in your account.',
                    'customerCardCharge' => $customerCardCharge,
                );
                return $data;
            }
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (Exception $e) {
            $stripeResponseBody = $e->getMessage();
            $data = array('status' => false, 'message' => $stripeResponseBody);
            return $data;
        }
    }

    public function refundCardValidationCharge($customerCardCharge) {
        try {
            // Refund
            $customerChargeRefund = \Stripe\Refund::create(array(
                        "charge" => $customerCardCharge->id,
            ));
            $data = array(
                'status' => true,
                'message' => 'card charge refund successfully',
                'customerChargeRefund' => $customerChargeRefund,
            );
            return $data;
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (Exception $e) {
            $stripeResponseBody = $e->getMessage();
            $data = array('status' => false, 'message' => $stripeResponseBody);
            return $data;
        }
    }

    public function ticketsList($id) {
        $user = User::find($id);
        return view($this->moduleView . '.viewDetailSections.tickets.index', compact('user'));
    }

    public function ticketsDatatable($id) {
        $tickets = Ticket::select('tickets.id', 'tickets.ticket_id', 'tickets.transaction_id', 'tickets.title', 'tickets.status', DB::raw('CONCAT(users.firstname," ",users.lastname) as userFullname'), DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname')
                )
                ->join('transactions', function ($join) {
                    $join->on('transactions.random_transaction_id', '=', 'tickets.transaction_id');
                })
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'tickets.user_id');
                })
                ->where('transactions.user_id', $id)
                ->groupBy('tickets.id');

        return Datatables::of($tickets)
                        ->addColumn('lastUpdateDate', function ($tickets) {
                            $comment = Comment::select(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d %H:%i:%s") AS date'))
                                    ->orderBy('created_at', 'DESC')
                                    ->where('ticket_id', $tickets->id)
                                    ->whereNotNull('admin_id')
                                    ->first();
                            if ($comment)
                                return $comment->date;
                            else
                                return " ";
                        })
                        ->addColumn('lastResponseAdminName', function ($tickets) {
                            $comment = Comment::select('admins.name', DB::raw('CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date'))
                                    ->join('admins', 'admins.id', '=', 'comments.admin_id')
                                    ->orderBy('comments.created_at', 'DESC')
                                    ->where('comments.ticket_id', $tickets->id)
                                    ->whereNotNull('comments.admin_id')
                                    ->first();
                            if ($comment)
                                return $comment->name;
                            else
                                return " ";
                        })
                        ->filterColumn('userFullname', function ($query, $keyword) {
                            $sql = "CONCAT(users.firstname,' ',users.lastname)  like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->filterColumn('debtorFullname', function ($query, $keyword) {
                            $sql = "CONCAT(transactions.debtor_firstname,' ',transactions.debtor_lastname) like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->make(true);
    }

    public function ticketsDetail($id, $ticket_id) {
        $user = User::find($id);
        $ticket = Ticket::where('ticket_id', $ticket_id)->where('user_id', $id)->firstOrFail();
        $comments = $ticket->comments;
        return view($this->moduleView . '.viewDetailSections.tickets.show', compact('ticket', 'comments', 'user'));
    }

    public function changeStatus($id, $ticket_id, Request $request) {
        $id = User::find($id);
        $ticket = Ticket::find($ticket_id);
        // return $ticket;
        $ticket->status = $request->status;

        $ticket->save();

        addToLog('Ticket status change successfully.', 'tickets', json_encode($ticket));        
        // $request->session()->flash('success', "The ticket has been closed.");

        $data = ['status' => true, 'message' => "The ticket status change successfully"];
        return Response::json($data);
    }

    public function postComment($id, Request $request) {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = Comment::create([
                    'ticket_id' => $request->input('ticket_id'),
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'comment' => $request->input('comment'),
        ]);

        // $request->session()->flash('success', "Your comment has be submitted.");
        $data = ['status' => true, 'message' => 'Comment Post Successfully', 'data' => $comment];
        return Response::json($data);
    }

    public function getComment($id, $ticket_id, Request $req) {
        $ticket = Ticket::select('comments.id', 'comments.admin_id', 'tickets.ticket_id', 'comments.user_id', 'tickets.title', 'tickets.message', 'tickets.title', 'comments.created_at', DB::raw('CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS createdDate'), 'users.firstname', 'users.lastname', 'admins.name', 'comments.comment')
                ->leftJoin('comments', 'comments.ticket_id', 'tickets.id')
                ->leftjoin('users', 'comments.user_id', 'users.id')
                ->leftjoin('admins', 'comments.admin_id', 'admins.id')
                ->where('tickets.ticket_id', $ticket_id);

        if (($last_id = $req->get('last_id')) != '') {
            $ticket = $ticket->where('comments.id', '>', $last_id);
        }

        $ticket = $ticket->get();
        $data = ['status' => true, 'message' => 'Comment get Successfully', 'data' => $ticket];
        return Response::json($data);
    }

}
