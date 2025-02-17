<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function createLog($action, $description, $id = null)
    {
        // TODO: also include ip address, device, and also failed attempts.
        Log::create([
            'user_id' => $id ?? Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
