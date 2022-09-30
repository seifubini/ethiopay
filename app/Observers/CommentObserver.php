<?php

namespace App\Observers;

use App\Jobs\CreateStripeCustomerAccountJob;
use App\Models\Comment;

class CommentObserver {

    public function creating(Comment $comment) {
        
    }
    
    public function created(Comment $comment) {
        addToLog('Comment was added succesfully.', 'comments', json_encode($comment));
    }
    
    public function updating(Comment $comment) {
        
    }
    
    public function updated(Comment $comment) {
                       
    }
    
    public function saving(Comment $comment) {
        
    }
    
    public function saved(Comment $comment) {
        
    }
    
    public function deleting(Comment $comment) {
        
    }
    
    public function deleted(Comment $comment) {
        
    }
    
    public function restoring(Comment $comment) {
        
    }

    public function restored(Comment $comment) {
        
    }

}

