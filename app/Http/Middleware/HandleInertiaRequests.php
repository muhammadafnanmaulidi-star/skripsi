<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'member_status' => $request->user() && $request->user()->phone_number 
                    ? \App\Models\Member::where('phone', $request->user()->phone_number)->value('status') 
                    : null,
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
                'payment_info' => session('payment_info'),
                'snap_token' => session('snap_token'),
            ],
            'midtrans_client_key' => config('services.midtrans.client_key'),
            'midtrans_is_production' => config('services.midtrans.is_production'),
            'asset_url' => asset(''),
        ];
    }
}
