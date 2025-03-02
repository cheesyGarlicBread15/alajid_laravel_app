<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class LogHelper
{
    public static function createLog(Request $request, $action, $description, $id = null)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        Log::create([
            'user_id' => $id ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip' => $request->ip(),
            'device_type' => $agent->isDesktop() ? 'Desktop' : ($agent->isMobile() ? 'Mobile' : 'Tablet'),
            'platform' => $agent->platform(),
        ]);
    }
}
