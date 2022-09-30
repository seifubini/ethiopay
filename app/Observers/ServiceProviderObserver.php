<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\ServiceProvider;

class ServiceProviderObserver {

    public function creating(ServiceProvider $serviceProvider) {
    
    }
    
    public function created(ServiceProvider $serviceProvider) {
       addToLog('Service provider was added succesfully.', 'service_providers', json_encode($serviceProvider));
    }
    
    public function updating(ServiceProvider $serviceProvider) {
        
    }
    
    public function updated(ServiceProvider $serviceProvider) {
        addToLog('Service provider was updated succesfully.', 'service_providers', json_encode($serviceProvider));        
    }
    
    public function saving(ServiceProvider $serviceProvider) {
        
    }
    
    public function saved(ServiceProvider $serviceProvider) {
        
    }
    
    public function deleting(ServiceProvider $serviceProvider) {
        
    }
    
    public function deleted(ServiceProvider $serviceProvider) {
        addToLog('Service provider was deleted succesfully.', 'service_providers', json_encode($serviceProvider));
    }
    
    public function restoring(ServiceProvider $serviceProvider) {
        
    }

    public function restored(ServiceProvider $serviceProvider) {
        
    }

}

