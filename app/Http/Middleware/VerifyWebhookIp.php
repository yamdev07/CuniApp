<?php // app/Http/Middleware/VerifyWebhookIp.php
namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyWebhookIp
{
    public function handle(Request $request, Closure $next)
    {
        $whitelist = Setting::get('webhook_ip_whitelist');
        
        // ✅ SECURITY FIX: Deny access in production if whitelist is empty
        if (empty($whitelist) && app()->environment('production')) {
            Log::channel('webhooks')->critical('Webhook: Whitelist empty in production', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);
            abort(403, 'Webhook IP whitelist not configured.');
        }

        if (!$whitelist) {
            // No whitelist configured (allowed in non-production)
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