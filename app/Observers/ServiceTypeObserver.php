<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\ServiceType;

class ServiceTypeObserver {

    public function creating(ServiceType $serviceType) {
    
    }
    
    public function created(ServiceType $serviceType) {
       addToLog('Service type was added succesfully.', 'service_types', json_encode($serviceType));
    }
    
    public function updating(ServiceType $serviceType) {
        
    }
    
    public function updated(ServiceType $serviceType) {
        addToLog('Service type was updated succesfully.', 'service_types', json_encode($serviceType));        
    }
    
    public function saving(ServiceType $serviceType) {
        
    }
    
    public function saved(ServiceType $serviceType) {
        
    }
    
    public function deleting(ServiceType $serviceType) {
        
    }
    
    public function deleted(ServiceType $serviceType) {
        addToLog('Service type was deleted succesfully.', 'service_types', json_encode($serviceType));
    }
    
    public function restoring(ServiceType $serviceType) {
        
    }

    public function restored(ServiceType $serviceType) {
        
    }

}

