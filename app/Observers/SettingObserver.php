<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\Setting;

class SettingObserver {

    public function creating(Setting $setting) {
        
    }
    
    public function created(Setting $setting) {

    }
    
    public function updating(Setting $setting) {
        
    }
    
    public function updated(Setting $setting) {
        addToLog('Setting was updated succesfully.', 'settings', json_encode($setting));               
    }
    
    public function saving(Setting $setting) {
        
    }
    
    public function saved(Setting $setting) {
        
    }
    
    public function deleting(Setting $setting) {
        
    }
    
    public function deleted(Setting $setting) {
        
    }
    
    public function restoring(Setting $setting) {
        
    }

    public function restored(Setting $setting) {
        
    }

}

