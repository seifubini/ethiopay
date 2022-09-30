<?php

use App\Models\ActivityLog;

if (!function_exists('addToLog')) { 
	function addToLog($subject, $type, $description)
    {
    	$log = [];
		$log['subject'] = $subject;
		$log['type'] = $type;
		$log['activity_description'] = $description;
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
		$log['admin_id'] = auth()->guard('admin')->user()->id;
    	ActivityLog::create($log);
    }
}


// class ActivityLogHelper
// {
	
//     public  function addToLog($subject)
//     {
//     	$log = [];
//     	$log['subject'] = $subject;
//     	$log['url'] = Request::fullUrl();
//     	$log['method'] = Request::method();
//     	$log['ip'] = Request::ip();
//     	$log['admin_id'] = Auth::guard('admin')->user()->id;
//     	ActivityLog::create($log);
//     }


//     public static function logActivityLists()
//     {
//     	return ActivityLog::latest()->get();
//     }
// }