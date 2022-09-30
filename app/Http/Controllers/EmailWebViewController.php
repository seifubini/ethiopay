<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\UidMissing;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\library\CommonFunction;
use App\Models\ServiceProvider;
use App\Models\SmsEmailMessage;
use Illuminate\Support\Facades\DB;

class EmailWebViewController extends Controller {

    //
    public function showUserRegistrationMailOnWeb($idEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $user = User::find($id);
        if ($user) {
            $isWebView = 1;
            return view('emails.register', compact('user', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserWelcomeMailOnWeb($idEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $user = User::find($id);
        if ($user) {
            $isWebView = 1;
            return view('emails.welcome', compact('user', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserServicePayedMailOnWeb($idEncrypted, $transactionIdEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $transactionId = CommonFunction::decodeForID($transactionIdEncrypted);

        $transaction = Transaction::find($transactionId);
        $user = User::find($id);
        $serviceProvider = ServiceProvider::where('id', $transaction->service_provider_id)->first();
        if ($user) {
            $isWebView = 1;
            return view('emails.servicePayed', compact('user', 'transaction', 'serviceProvider', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserServicePayedFailMailOnWeb($idEncrypted, $transactionIdEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $transactionId = CommonFunction::decodeForID($transactionIdEncrypted);

        $transaction = Transaction::find($transactionId);
        $user = User::find($id);
        $serviceProvider = ServiceProvider::where('id', $transaction->service_provider_id)->first();
        if ($user) {
            $isWebView = 1;
            return view('emails.servicePayedFail', compact('user', 'transaction', 'serviceProvider', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserForgotPasswordMailOnWeb($idEncrypted, $token) {

        $id = CommonFunction::decodeForID($idEncrypted);
        $user = User::find($id);
        $outroLines = [$user->email, $user->firstname, $user->id, 'user'];
        $actionUrl = url('password/reset') . '/' . $token;
        if ($user) {
            $isWebView = 1;
            return view('vendor.notifications.email', compact('outroLines', 'isWebView', 'actionUrl'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showAdminForgotPasswordMailOnWeb($idEncrypted, $token) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $user = Admin::find($id);
        $outroLines = [$user->email, $user->firstname, $user->id, 'admin'];
        $actionUrl = url('admin/password/reset') . '/' . $token;
        if ($user) {
            $isWebView = 1;
            return view('vendor.notifications.email', compact('outroLines', 'isWebView', 'actionUrl'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUsermonthlyServicesPayedMailOnWeb($idEncrypted, $firstDayofPreviousMonthEncrypted, $lastDayofPreviousMonthEncrypted) {
        $firstDayofPreviousMonth = new Carbon(urldecode($firstDayofPreviousMonthEncrypted));
        $lastDayofPreviousMonth = new Carbon(urldecode($lastDayofPreviousMonthEncrypted));

        $id = CommonFunction::decodeForID($idEncrypted);
        $user = User::find($id);

        $transactions = Transaction::select('service_types.service_name', 'transactions.user_id', DB::raw("COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0) AS transactionsAmountSum"), DB::raw("COUNT(transactions.id) as totalTransaction"))
                ->leftJoin('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->leftJoin('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                ->where('transactions.user_id', $id)
                ->whereBetween('transactions.created_at', [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                ->groupBy('service_types.id')
                ->get();
        if ($user) {
            $isWebView = 1;
            return view('emails.monthlyServicesPayed', compact('isWebView', 'user', 'transactions'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserCardExpireWarningMailOnWeb($idEncrypted, $paymentMethodIdEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $paymentMethodId = CommonFunction::decodeForID($paymentMethodIdEncrypted);

        $user = User::find($id);
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if ($user) {
            $isWebView = 1;
            return view('emails.cardExpire', compact('user', 'paymentMethod', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUidMissingMailOnWeb($userIdEncrypted, $idEncrypted) {
        $user_id = CommonFunction::decodeForID($userIdEncrypted);
        $id = CommonFunction::decodeForID($idEncrypted);

        $uidMissing = UidMissing::with(['serviceTypeData', 'userData'])->where('id', $id)->where('user_id', $user_id)->first();
        if ($uidMissing) {
            $user = $uidMissing->userData;
            $isWebView = 1;
            return view('emails.admin.uid-missing-service-available', compact('isWebView', 'user', 'uidMissing'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showUserEmailMessageMailOnWeb($idEncrypted, $emailMessageIdEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $email_message_id = CommonFunction::decodeForID($emailMessageIdEncrypted);

        $user = User::find($id);        
        $emailMessage = SmsEmailMessage::find($email_message_id);
        if ($emailMessage) {
            $isWebView = 1;
            return view('emails.admin.userEmailMessage', compact('isWebView', 'user', 'emailMessage'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }

    public function showbillExpirationWarningMailOnWeb($idEncrypted) {
        $id = CommonFunction::decodeForID($idEncrypted);
        $user = User::find($id);
        if ($user) {
            $isWebView = 1;
            return view('emails.billExpirationWarning', compact('user', 'isWebView'));
        } else {
            abort('404', 'Page Not Found.');
        }
    }
}
