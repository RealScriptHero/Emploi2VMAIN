<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApplySettings
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Schema::hasTable((new Setting())->getTable())) {
            $settings = Setting::getAll();

            $language = in_array($settings['language'] ?? null, ['fr', 'en'])
                ? $settings['language']
                : config('app.locale');
            app()->setLocale($language);
            view()->share('appSettings', $settings);
        }

        return $next($request);
    }
}
