<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    public static function logGradeAction($action, $data, $user)
    {
        Log::channel('grades')->info("{$action} performed", [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'data' => $data,
            'ip_address' => request()->ip(),
            'timestamp' => now()
        ]);
    }
}
