<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });

        $dashboardLayouts = [
            'layouts.dashboard',
            'layouts.agent-detail',
            'layouts.agents-list',
            'layouts.catalog',
            'layouts.connectors-user',
            'layouts.profile',
        ];

        View::composer($dashboardLayouts, function ($view) {
            $chatEndpoint = '';

            if (Auth::check()) {
                $hasActiveChatConnector = Auth::user()
                    ->userConnectors()
                    ->where('status', 'active')
                    ->whereHas('connector', fn($q) => $q->where('slug', 'custom-chat-api'))
                    ->exists();

                if ($hasActiveChatConnector) {
                    $chatEndpoint = route('dashboard.chat');
                }
            }

            $view->with('chatEndpoint', $chatEndpoint);
        });
    }
}
