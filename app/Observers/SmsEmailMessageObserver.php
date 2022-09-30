<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\SmsEmailMessage;

class SmsEmailMessageObserver {

    public function creating(SmsEmailMessage $smsEmailMessage) {
    
    }
    
    public function created(SmsEmailMessage $smsEmailMessage) {
       addToLog('SMS/Email was added succesfully.', 'sms_email_messages', json_encode($smsEmailMessage));
    }
    
    public function updating(SmsEmailMessage $smsEmailMessage) {
        
    }
    
    public function updated(SmsEmailMessage $smsEmailMessage) {
        addToLog('SMS/Email was updated succesfully.', 'sms_email_messages', json_encode($smsEmailMessage));        
    }
    
    public function saving(SmsEmailMessage $smsEmailMessage) {
        
    }
    
    public function saved(SmsEmailMessage $smsEmailMessage) {
        
    }
    
    public function deleting(SmsEmailMessage $smsEmailMessage) {
        
    }
    
    public function deleted(SmsEmailMessage $smsEmailMessage) {
        addToLog('SMS/Email was deleted succesfully.', 'sms_email_messages', json_encode($smsEmailMessage));
    }
    
    public function restoring(SmsEmailMessage $smsEmailMessage) {
        
    }

    public function restored(SmsEmailMessage $smsEmailMessage) {
        
    }

}

