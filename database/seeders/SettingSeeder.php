<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Wipe all settings, then seed defaults (same shapes as admin / production updates).
     */
    public function run(): void
    {
        Setting::query()->delete();

        $countryId = DB::table('countries')->where('status', 1)->orderBy('id')->value('id');
        $currency = 'USD';
        if ($countryId) {
            $code = DB::table('currencies')->where('country_id', $countryId)->value('code');
            if ($code) {
                $currency = $code;
            }
        }

        $commission = '25';

        $userReminderMessages = [
            '15m' => 'Your booked service will begin in 15 minutes. Be available at your chosen location.',
            '30m' => 'Your booked service will begin in 30 minutes. Be available at your chosen location.',
            '45m' => 'Your booked service will begin in 45 minutes. Be available at your chosen location.',
        ];

        $providerReminderMessages = [
            '15m' => 'You have an upcoming booking starting in 15 minutes. Be available at the service location.',
            '30m' => 'You have an upcoming booking starting in 30 minutes. Be available at the service location.',
            '45m' => 'You have an upcoming booking starting in 45 minutes. Be available at the service location.',
        ];

        $reminderTimes = json_encode(['15m', '30m', '45m']);

        // financial (matches SettingsManager::saveFinancial)
        if ($countryId) {
            Setting::set('country_id', (string) $countryId, 'financial');
        }
        Setting::set('currency', $currency, 'financial');
        Setting::set('service_charge_percentage', $commission, 'financial');
        Setting::set('commission_fee', $commission, 'financial');

        // notification — booleans stored as '1' / '0' (same as writeBoolSetting)
        Setting::set('push_notifications', '1', 'notification');
        Setting::set('email_notifications', '1', 'notification');
        Setting::set('sms_notifications', '0', 'notification');
        Setting::set('user_reminder_enabled', '0', 'notification');
        Setting::set('provider_reminder_enabled', '0', 'notification');
        Setting::set('user_reminder_times', $reminderTimes, 'notification');
        Setting::set('provider_reminder_times', $reminderTimes, 'notification');
        Setting::set('user_reminder_messages', json_encode($userReminderMessages), 'notification');
        Setting::set('provider_reminder_messages', json_encode($providerReminderMessages), 'notification');

        // onboarding / content (matches terms page fallbacks + admin placeholders)
        Setting::set('onboarding_intro', 'Welcome to Flyertrade. This overview explains how we present information when you join our service marketplace.', 'onboarding');
        Setting::set('onboarding_info_collect', 'We collect account details, contact information, and information you provide when booking or listing services, so we can operate the platform and fulfil your requests.', 'onboarding');
        Setting::set('onboarding_use_info', 'We use your information to provide and improve the service, process bookings and payments, communicate with you, and meet legal obligations.', 'onboarding');
        Setting::set('onboarding_disclosure', 'We may share information with service providers, payment processors, and authorities where required by law. We do not sell your personal data.', 'onboarding');

        Setting::set('onboarding_terms_agreement', 'By accessing or using Flyertrade, you agree to be bound by these terms and conditions. If you disagree with any part of these terms, you may not access the service.', 'onboarding');
        Setting::set('onboarding_terms_use', 'You agree to use our service only for lawful purposes and in accordance with these terms. You are responsible for maintaining the confidentiality of your account and password.', 'onboarding');
        Setting::set('onboarding_terms_ip', 'The service and its original content, features, and functionality are and will remain the exclusive property of Flyertrade and its licensors.', 'onboarding');
        Setting::set('onboarding_terms_termination', 'We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the terms.', 'onboarding');
        Setting::set('onboarding_terms_liability', 'In no event shall Flyertrade, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages.', 'onboarding');
        Setting::set('onboarding_terms_law', 'These terms shall be governed and construed in accordance with the laws of our operating jurisdiction, without regard to its conflict of law provisions.', 'onboarding');
        Setting::set('onboarding_terms_changes', 'We reserve the right, at our sole discretion, to modify or replace these terms at any time. We will provide at least 30 days\' notice prior to any new terms taking effect.', 'onboarding');
        Setting::set('onboarding_terms_contact', 'If you have any questions about these terms, please contact us at: support@flyertrade.com', 'onboarding');
    }
}
