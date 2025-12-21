<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Login $event): void
    {
        $user = $event->user;
        $userAgent = $this->request->userAgent();

        LoginLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'ip_address' => $this->request->ip(),
            'user_agent' => $userAgent,
            'browser' => $this->parseBrowser($userAgent),
            'platform' => $this->parsePlatform($userAgent),
        ]);
    }

    protected function parseBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) return null;

        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Edg')) return 'Edge';
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';

        return 'Unknown';
    }

    protected function parsePlatform(?string $userAgent): ?string
    {
        if (!$userAgent) return null;

        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'macOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) return 'iOS';

        return 'Unknown';
    }
}
