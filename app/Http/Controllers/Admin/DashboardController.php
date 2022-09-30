<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Config;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller {

    public function __construct() {
        $this->moduleName = "Dashboard";
        $this->moduleRoute = url('admin/');
        $this->moduleView = "admin.dashboard";
        
        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $todayDate = Carbon::now()->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d');
        $totalUser = User::select(DB::raw('COUNT(*) as totalUser'))->first();
        $todayUser = User::select(DB::raw('COUNT(*) as todayUser'))
                    ->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), $todayDate)
                    ->first();
        $totalTransaction = Transaction::select(DB::raw('COUNT(*) as totalTransaction'))->first();
        $todayTransaction = Transaction::select(DB::raw('COUNT(*) as todayTransaction'))
                    ->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), $todayDate)
                    ->first();

        return view($this->moduleView . ".index", compact('totalUser', 'totalTransaction', 'todayUser', 'todayTransaction'));
    }
    
    public function averageTrnsactionChartData(Request $request, $chartType) {
        $currentDate = Carbon::now();
//        $currentDate = Carbon::parse('2017-11-16', config('ethiopay.TIMEZONE_STR'));

        $totalTransactionChartDataSet = [];

        $graphs = Transaction::select('service_types.service_name', 'transactions.total_pay_amount as total_amount', DB::raw('GROUP_CONCAT(transactions.total_pay_amount) as amount'))
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
                $transactionChartRes = Transaction::select('service_types.service_name', DB::raw('sum(transactions.total_pay_amount) as total_amount'), DB::raw("UCASE(DAYNAME(CONVERT_TZ(TIMESTAMP(transactions.created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))) dayName"))
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

        $fillcolor = ["rgba(0, 146, 69, 0.2)", "rgba(147, 197, 22, 0.2)", "rgba(22, 197, 94, 0.2)",
            "rgba(255, 0, 0, 0.2)", "rgba(0, 0, 255, 0.2)", "rgba(255, 99, 71, 0.2)",
            "rgba(100, 43, 162, 0.2)", "rgba(248, 43, 123, 0.2)", "rgba(248, 129, 149, 0.2)",
            "rgba(111, 129, 149, 0.2)", "rgba(111, 129, 47, 0.2)", "rgba(60, 28, 16, 0.2)",
            "rgba(60, 134, 16, 0.2)", "rgba(0, 255, 255, 0.2)", "rgba(255, 255, 0, 0.2)",
            "rgba(255, 150, 211, 0.2)", "rgba(255, 150, 64, 0.2)", "rgba(196, 150, 191, 0.2)",
            "rgba(196, 150, 93, 0.2)", "rgba(84, 150, 204, 0.2)", "rgba(84, 17, 204, 0.2)",
        ];

        $colorArrayCount = count($borderColor);

        for ($i = 0; $i < count($totalTransactionChartDataSet) - $colorArrayCount; $i++) {
            $borderColor[] = "rgba(0, 0, 255, 1)";
            $fillcolor[] = "rgba(0, 0, 255, 0.2)";
        }

        $totalDataset = [];

        for ($i = 0; $i < count($totalTransactionChartDataSet); $i++) {
            $dataset = [];
            $dataset = [
                "data" => $totalTransactionChartDataSet[$i],
                "backgroundColor" => $fillcolor[$i],
                "borderColor" => $borderColor[$i],
                "fillColor" => $fillcolor[$i],
                "borderWidth" => 1,
                "pointRadius" => 1,
                "lineTension" => 0.2,
                "fill" => true,
            ];
            $totalDataset[] = $dataset;
        }

        return response()->json(['transactionChartLabels' => $transactionChartLabels, 'transactionChartDataSet' => $totalTransactionChartDataSet, 'totalDataSet' => $totalDataset, 'serviceName' => $graphs, 'color' => $borderColor]);
    }

    public function newCustomersChartData($chartType) {
        $currentDate = Carbon::now(config('ethiopay.TIMEZONE_STR'));
//        $currentDate = Carbon::parse('2017-11-16', config('ethiopay.TIMEZONE_STR'));

        $newCustomersChartLabels = [];
        $newCustomersChartDataSet = [];
        if ($chartType == 'day') {
            $fromDate = $currentDate->startOfDay()->format('Y-m-d H:i:s');

            $newCustomersChartRes = User::select([
                                    DB::raw("HOUR(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')) dateHour"),
                                    DB::raw("COUNT(id) as totalCustomers")
                                ])
                            ->where(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $fromDate)
                            ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                            ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                            ->get()->toArray();

            $newCustomersChartResDayDate = array_column($newCustomersChartRes, 'dateHour');

            $hoursInDay = [];
            for ($i = 0; $i <= 24; $i++) {
                $hoursInDay[] = ($i < 10) ? $i : $i;
            }

            $newCustomersChartResIndex = 0;
            foreach ($hoursInDay as $key => $hour) {
                if (in_array($hour, $newCustomersChartResDayDate)) {
                    $newCustomersChartDataSet[] = $newCustomersChartRes[$newCustomersChartResIndex]['totalCustomers'];
                    $newCustomersChartLabels[] = ($hour < 10) ? '0' . (string) $hour : (string) $hour;
                    $newCustomersChartResIndex++;
                } else {
                    $newCustomersChartDataSet[] = 0;
                    $newCustomersChartLabels[] = ($hour < 10) ? '0' . (string) $hour : (string) $hour;
                }
            }
        } else if ($chartType == 'week') {
            $fromDate = $currentDate->startOfWeek()->format('Y-m-d H:i:s');
            $newCustomersChartRes = User::select([
                                DB::raw("UCASE(DAYNAME(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))) dayName"),
                                DB::raw("COUNT(id) as totalCustomers")
                            ])
                            ->where(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $fromDate)
                            ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                            ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                            ->get()->toArray();

            $newCustomersChartResDayName = array_column($newCustomersChartRes, 'dayName');
            $newCustomersChartLabels = [ 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];
    
            $newCustomersChartResIndex = 0;
            foreach ($newCustomersChartLabels as $key => $weekDay) {
                if (in_array($weekDay, $newCustomersChartResDayName)) {
                    $newCustomersChartDataSet[] = $newCustomersChartRes[$newCustomersChartResIndex]['totalCustomers'];
                    $newCustomersChartResIndex++;
                } else {
                    $newCustomersChartDataSet[] = 0;
                }
            }
        } else if ($chartType == 'month') {
            $fromDate = $currentDate->startOfMonth()->format('Y-m-d H:i:s');
            $newCustomersChartRes = User::select([
                                DB::raw("DAY(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')) dateDay"),
                                DB::raw("COUNT(id) as totalCustomers")
                            ])
                            ->where(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $fromDate)
                            ->groupBy(DB::raw("DATE(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                            ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                            ->get()->toArray();

            $newCustomersChartResDayDate = array_column($newCustomersChartRes, 'dateDay');
            $newCustomersChartLabels = [];
            $newCustomersChartDataSet = [];

            $daysInMonthArr = [];
            for ($i = 1; $i <= $currentDate->daysInMonth; $i++) {
                $daysInMonthArr[] = ($i < 10) ? $i : $i;
            }

            $newCustomersChartResIndex = 0;
            foreach ($daysInMonthArr as $key => $dateDay) {
                if (in_array($dateDay, $newCustomersChartResDayDate)) {
                    $newCustomersChartDataSet[] = $newCustomersChartRes[$newCustomersChartResIndex]['totalCustomers'];
                    $newCustomersChartLabels[] = ($dateDay < 10) ? '0' . (string) $dateDay : (string) $dateDay;
                    $newCustomersChartResIndex++;
                } else {
                    $newCustomersChartDataSet[] = 0;
                    $newCustomersChartLabels[] = ($dateDay < 10) ? '0' . (string) $dateDay : (string) $dateDay;
                }
            }
        } else if ($chartType == 'year') {
            $fromDate = $currentDate->startOfYear()->format('Y-m-d H:i:s');
            $newCustomersChartRes = User::select([
                                DB::raw("UCASE(MONTHNAME(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))) monthName"),
                                DB::raw("COUNT(id) as totalCustomers")
                            ])
                            ->where(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), '>=', $fromDate)
                            ->groupBy(DB::raw("MONTHNAME(CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "'))"))
                            ->orderBy(DB::raw("CONVERT_TZ(TIMESTAMP(created_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "')"), 'asc')
                            ->get()->toArray();

            $newCustomersChartResMonthName = array_column($newCustomersChartRes, 'monthName');
            $newCustomersChartLabels = [ 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

            $newCustomersChartResIndex = 0;
            foreach ($newCustomersChartLabels as $key => $monthName) {
                if (in_array($monthName, $newCustomersChartResMonthName)) {
                    $newCustomersChartDataSet[] = $newCustomersChartRes[$newCustomersChartResIndex]['totalCustomers'];
                    $newCustomersChartResIndex++;
                } else {
                    $newCustomersChartDataSet[] = 0;
                }
            }
        }
        return response()->json([ 'newCustomersChartLabels' => $newCustomersChartLabels, 'newCustomersChartDataSet' => $newCustomersChartDataSet]);
    }
}
