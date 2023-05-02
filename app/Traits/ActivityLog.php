<?php

namespace App\Traits;

use App\Models\ActicityLog;

trait ActivityLog
{
    public function addLog($msg,$health_record_id,$description)
    {
        $activity = new ActicityLog();
        $activity->operation_type = $msg;
        $activity->health_record_id = $health_record_id;
        $activity->description =$description;
        $activity->save();
    }
}