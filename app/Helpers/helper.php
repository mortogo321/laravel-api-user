<?php

if (!function_exists('isDevelopment')) {
    function isDevelopment(): bool
    {
        return in_array(config('app.env'), ['local', 'development', 'staging']);
    }
}

if (!function_exists('apiRequestData')) {
    function apiRequestData(): array
    {
        $user = auth()->user();
        $ip = request()->ip();
        $data = [
            'ipAddress' => $ip,
            'loginIp' => $ip,
        ];

        $headers = [
            'x-api-key' => $user->apiKey ?? '',
        ];

        if ($user) {
            $data = [
                ...$data,
                'countryCode' => strtoupper($user->orgUnitConfig->countryCode),
                'country' => strtoupper($user->orgUnitConfig->countryCode),
                'currency' => strtoupper($user->orgUnitConfig->currency),
                'language' => strtolower($user->orgUnitConfig->lang),
                'orgUnitCode' => strtoupper($user->orgUnitCode),
            ];

            if (request()->isMethod('put')) {
                $data['modifiedBy'] = $user->loginId;
            }

            $headers['X-AUTH-TOKEN'] = $user->token;
        }

        return [$data, $headers];
    }
}
