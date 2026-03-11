<?php
// app/Http/Middleware/VerifyWebhookIP.php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyWebhookIP
{
    public function handle(Request $request, Closure $next)
    {
        $whitelist = Setting::get('webhook_ip_whitelist');
        
        if (!$whitelist) {
            // No whitelist configured, allow all
            return $next($request);
        }
        
        $allowedIps = array_map('trim', explode(',', $whitelist));
        $requestIp = $request->ip();
        
        if (!in_array($requestIp, $allowedIps)) {
            Log::channel('webhooks')->warning('Webhook: IP not in whitelist', [
                'ip' => $requestIp,
                'allowed' => $allowedIps,
                'path' => $request->path(),
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'IP not authorized',
            ], 403);
        }
        
        return $next($request);
    }
}