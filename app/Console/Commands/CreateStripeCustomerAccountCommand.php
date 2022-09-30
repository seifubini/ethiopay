<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\User;

class CreateStripeCustomerAccountCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:create-stripe-customer-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create stripe customer account for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $users = User::where('stripe_customer_id', '')->get();

        foreach ($users as $key => $user) {
            try {
                $stripeCustomerAccount = \Stripe\Customer::create(array(
                            "email" => $user->email,
                            "description" => env("STRIPE_PRIFIX_TEXT", "") . $user->email,
                            "metadata" => ["id" => $user->id, "email" => $user->email],
                ));
                if ($stripeCustomerAccount) {
                    $user->stripe_customer_id = $stripeCustomerAccount->id;
                    $user->save();
                } else {
                    Log::info(['status' => false, 'message' => 'Something went wrong in create stripe customer account', 'user' => $user]);
                }
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            } catch (\Stripe\Error\Base $e) {
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            } catch (Exception $e) {
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);
                //return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
            }
        }
    }

}
