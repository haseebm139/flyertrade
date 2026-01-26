<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

use App\Observers\UserObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            User::observe(UserObserver::class);
            Paginator::defaultView('vendor.pagination.custom');

            ResetPassword::toMailUsing(function ($notifiable, string $token) {
                $url = url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false));

                $fromAddress = config('mail.from.address', 'haseebm139@gmail.com');
                $fromName = config('mail.from.name', config('app.name'));
                $expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

                return (new MailMessage)
                    ->from($fromAddress, $fromName)
                    ->subject('Reset Password Notification')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $url)
                    ->line("This password reset link will expire in {$expire} minutes.")
                    ->line('If you did not request a password reset, no further action is required.');
            });

    }
}
