<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateStripeCustomerAccountJob implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $user = $this->user;
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
