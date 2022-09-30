<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\User;

class UserObserver {

    public function creating(User $user) {
        
    }
    
    public function created(User $user) {
        CreateStripeCustomerAccountJob::dispatch($user)->onQueue('stripe');
    }
    
    public function updating(User $user) {
        
    }
    
    public function updated(User $user) {

    }
    
    public function saving(User $user) {
        
    }
    
    public function saved(User $user) {
        
    }
    
    public function deleting(User $user) {
        
    }
    
    public function deleted(User $user) {
       addToLog('User was deleted succesfully.', 'users', json_encode($user));   
    }
    
    public function restoring(User $user) {
        
    }

    public function restored(User $user) {
        
    }

}

