<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\AdminUserMessage;

class AdminUserMessageObserver {

    public function creating(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function created(AdminUserMessage $adminUserMessage) {
        addToLog('Message was added succesfully.', 'admin_user_messages', json_encode($adminUserMessage));
    }
    
    public function updating(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function updated(AdminUserMessage $adminUserMessage) {
                       
    }
    
    public function saving(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function saved(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function deleting(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function deleted(AdminUserMessage $adminUserMessage) {
        
    }
    
    public function restoring(AdminUserMessage $adminUserMessage) {
        
    }

    public function restored(AdminUserMessage $adminUserMessage) {
        
    }

}

