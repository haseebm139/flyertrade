<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class SettingsManager extends Component
{
    #[Url(history: true)]
    public $activeTab = 'financial';

    protected array $allowedTabs = [
        'general',
        'financial',
        'notification',
        'system',
        'admin',
        'onboarding',
        'content',
    ];
    
    // Financial Settings
    public $country_id;
    public $currency;
    public $commission_fee;
    public $currencyAuto = true;
    
    // Notification Settings
    public $push_notifications;
    public $email_notifications;
    public $sms_notifications;
    public $user_reminder_enabled = false;
    public $provider_reminder_enabled = false;
    public $user_reminder_times = [];
    public $provider_reminder_times = [];
    public $user_reminder_message_15 = '';
    public $user_reminder_message_60 = '';
    public $user_reminder_message_1d = '';
    public $provider_reminder_message_15 = '';
    public $provider_reminder_message_60 = '';
    public $provider_reminder_message_1d = '';
    public $editingMessageKey = null;
    public $editingMessageValue = '';
    public $onboarding_intro = '';
    public $onboarding_info_collect = '';
    public $onboarding_use_info = '';
    public $onboarding_disclosure = '';
    public $onboardingEditingKey = null;
    public $onboardingEditingValue = '';
    
    public function mount()
    {
        $this->loadSettings();
    }
    
    public function loadSettings()
    {
        if (!in_array($this->activeTab, $this->allowedTabs, true)) {
            $this->activeTab = 'financial';
        }

        $this->country_id = Setting::get('country_id');
        $this->currency = Setting::get('currency', 'USD');
        $this->commission_fee = Setting::get('service_charge_percentage', Setting::get('commission_fee', 0));

        $this->push_notifications = (bool) Setting::get('push_notifications', true);
        $this->email_notifications = (bool) Setting::get('email_notifications', true);
        $this->sms_notifications = (bool) Setting::get('sms_notifications', false);

        $this->user_reminder_enabled = (bool) Setting::get('user_reminder_enabled', false);
        $this->provider_reminder_enabled = (bool) Setting::get('provider_reminder_enabled', false);
        $this->user_reminder_times = json_decode(Setting::get('user_reminder_times', '[]'), true) ?: [];
        $this->provider_reminder_times = json_decode(Setting::get('provider_reminder_times', '[]'), true) ?: [];
        $this->user_reminder_message_15 = Setting::get('user_reminder_message_15', '');
        $this->user_reminder_message_60 = Setting::get('user_reminder_message_60', '');
        $this->user_reminder_message_1d = Setting::get('user_reminder_message_1d', '');
        $this->provider_reminder_message_15 = Setting::get('provider_reminder_message_15', '');
        $this->provider_reminder_message_60 = Setting::get('provider_reminder_message_60', '');
        $this->provider_reminder_message_1d = Setting::get('provider_reminder_message_1d', '');
        $this->onboarding_intro = Setting::get('onboarding_intro', '');
        $this->onboarding_info_collect = Setting::get('onboarding_info_collect', '');
        $this->onboarding_use_info = Setting::get('onboarding_use_info', '');
        $this->onboarding_disclosure = Setting::get('onboarding_disclosure', '');
    }

    public function updatedCountryId($value)
    {
        if (!$this->currencyAuto) {
            return;
        }

        $currencyCode = DB::table('currencies')
            ->where('country_id', $value)
            ->value('code');

        if ($currencyCode) {
            $this->currency = $currencyCode;
        }
    }

    public function updatedCurrency()
    {
        $this->currencyAuto = false;
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function saveFinancial()
    {
        $this->validate([
            'commission_fee' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('country_id', $this->country_id, 'financial');
        Setting::set('currency', $this->currency, 'financial');
        Setting::set('service_charge_percentage', $this->commission_fee, 'financial');
        Setting::set('commission_fee', $this->commission_fee, 'financial');
        
        $this->dispatch('showSweetAlert', 'success', 'Financial settings updated successfully!', 'Success');
    }

    public function saveNotifications()
    {
        Setting::set('push_notifications', $this->push_notifications, 'notification');
        Setting::set('email_notifications', $this->email_notifications, 'notification');
        Setting::set('sms_notifications', $this->sms_notifications, 'notification');
        Setting::set('user_reminder_enabled', $this->user_reminder_enabled, 'notification');
        Setting::set('provider_reminder_enabled', $this->provider_reminder_enabled, 'notification');
        Setting::set('user_reminder_times', json_encode(array_values($this->user_reminder_times)), 'notification');
        Setting::set('provider_reminder_times', json_encode(array_values($this->provider_reminder_times)), 'notification');
        Setting::set('user_reminder_message_15', $this->user_reminder_message_15, 'notification');
        Setting::set('user_reminder_message_60', $this->user_reminder_message_60, 'notification');
        Setting::set('user_reminder_message_1d', $this->user_reminder_message_1d, 'notification');
        Setting::set('provider_reminder_message_15', $this->provider_reminder_message_15, 'notification');
        Setting::set('provider_reminder_message_60', $this->provider_reminder_message_60, 'notification');
        Setting::set('provider_reminder_message_1d', $this->provider_reminder_message_1d, 'notification');
        
        $this->dispatch('showSweetAlert', 'success', 'Notification settings updated successfully!', 'Success');
    }

    public function editMessage(string $key)
    {
        $this->editingMessageKey = $key;
        $this->editingMessageValue = match ($key) {
            'user_15' => $this->user_reminder_message_15,
            'user_60' => $this->user_reminder_message_60,
            'user_1d' => $this->user_reminder_message_1d,
            'provider_15' => $this->provider_reminder_message_15,
            'provider_60' => $this->provider_reminder_message_60,
            'provider_1d' => $this->provider_reminder_message_1d,
            default => '',
        };
    }

    public function saveMessage()
    {
        $key = $this->editingMessageKey;
        $value = trim((string) $this->editingMessageValue);

        if (!$key) {
            return;
        }

        switch ($key) {
            case 'user_15':
                $this->user_reminder_message_15 = $value;
                Setting::set('user_reminder_message_15', $value, 'notification');
                break;
            case 'user_60':
                $this->user_reminder_message_60 = $value;
                Setting::set('user_reminder_message_60', $value, 'notification');
                break;
            case 'user_1d':
                $this->user_reminder_message_1d = $value;
                Setting::set('user_reminder_message_1d', $value, 'notification');
                break;
            case 'provider_15':
                $this->provider_reminder_message_15 = $value;
                Setting::set('provider_reminder_message_15', $value, 'notification');
                break;
            case 'provider_60':
                $this->provider_reminder_message_60 = $value;
                Setting::set('provider_reminder_message_60', $value, 'notification');
                break;
            case 'provider_1d':
                $this->provider_reminder_message_1d = $value;
                Setting::set('provider_reminder_message_1d', $value, 'notification');
                break;
        }

        $this->editingMessageKey = null;
        $this->editingMessageValue = '';
    }

    public function cancelMessage()
    {
        $this->editingMessageKey = null;
        $this->editingMessageValue = '';
    }

    public function editOnboarding(string $key)
    {
        $this->onboardingEditingKey = $key;
        $this->onboardingEditingValue = match ($key) {
            'intro' => $this->onboarding_intro,
            'info_collect' => $this->onboarding_info_collect,
            'use_info' => $this->onboarding_use_info,
            'disclosure' => $this->onboarding_disclosure,
            default => '',
        };
    }

    public function saveOnboarding()
    {
        $key = $this->onboardingEditingKey;
        $value = trim((string) $this->onboardingEditingValue);

        if (!$key) {
            return;
        }

        switch ($key) {
            case 'intro':
                $this->onboarding_intro = $value;
                Setting::set('onboarding_intro', $value, 'onboarding');
                break;
            case 'info_collect':
                $this->onboarding_info_collect = $value;
                Setting::set('onboarding_info_collect', $value, 'onboarding');
                break;
            case 'use_info':
                $this->onboarding_use_info = $value;
                Setting::set('onboarding_use_info', $value, 'onboarding');
                break;
            case 'disclosure':
                $this->onboarding_disclosure = $value;
                Setting::set('onboarding_disclosure', $value, 'onboarding');
                break;
        }

        $this->onboardingEditingKey = null;
        $this->onboardingEditingValue = '';
    }

    public function cancelOnboarding()
    {
        $this->onboardingEditingKey = null;
        $this->onboardingEditingValue = '';
    }
    
    public function render()
    {
        $countries = \DB::table('countries')
            ->leftJoin('currencies', 'countries.id', '=', 'currencies.country_id')
            ->select(
                'countries.id',
                'countries.name',
                'countries.emoji',
                'countries.iso2',
                'countries.phone_code',
                'currencies.code as currency_code',
                'currencies.symbol as currency_symbol',
                'currencies.name as currency_name'
            )
            ->where('countries.status', 1)
            ->orderBy('countries.name')
            ->get()
            ->map(function ($country) {
                $country->flag_url = "assets/images/flags/" . strtolower($country->iso2) . ".png";
                return $country;
            });

        return view('livewire.admin.settings.settings-manager', [
            'countries' => $countries
        ]);
    }

    public function flagEmoji($iso2)
    {
        if (!$iso2) {
            return '';
        }

        $code = strtoupper($iso2);
        if (strlen($code) !== 2) {
            return '';
        }

        $offset = 127397;
        $chars = [
            $offset + ord($code[0]),
            $offset + ord($code[1]),
        ];

        return html_entity_decode('&#' . $chars[0] . ';&#' . $chars[1] . ';', ENT_NOQUOTES, 'UTF-8');
    }
}
