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
    /** @var array<string, string> */
    public $user_reminder_messages = [];
    /** @var array<string, string> */
    public $provider_reminder_messages = [];
    public $editingMessageKey = null;
    public $editingMessageValue = '';
    public $onboarding_intro = '';
    public $onboarding_info_collect = '';
    public $onboarding_use_info = '';
    public $onboarding_disclosure = '';
    public $onboarding_terms_agreement = '';
    public $onboarding_terms_use = '';
    public $onboarding_terms_ip = '';
    public $onboarding_terms_termination = '';
    public $onboarding_terms_liability = '';
    public $onboarding_terms_law = '';
    public $onboarding_terms_changes = '';
    public $onboarding_terms_contact = '';
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

        $this->push_notifications = $this->readBoolSetting('push_notifications', true);
        $this->email_notifications = $this->readBoolSetting('email_notifications', true);
        $this->sms_notifications = $this->readBoolSetting('sms_notifications', false);

        $this->user_reminder_enabled = $this->readBoolSetting('user_reminder_enabled', false);
        $this->provider_reminder_enabled = $this->readBoolSetting('provider_reminder_enabled', false);
        $this->user_reminder_times = $this->normalizeReminderTimes(json_decode(Setting::get('user_reminder_times', '[]'), true));
        $this->provider_reminder_times = $this->normalizeReminderTimes(json_decode(Setting::get('provider_reminder_times', '[]'), true));
        $this->user_reminder_messages = $this->loadReminderMessagesMap('user');
        $this->provider_reminder_messages = $this->loadReminderMessagesMap('provider');
        $this->onboarding_intro = Setting::get('onboarding_intro', '');
        $this->onboarding_info_collect = Setting::get('onboarding_info_collect', '');
        $this->onboarding_use_info = Setting::get('onboarding_use_info', '');
        $this->onboarding_disclosure = Setting::get('onboarding_disclosure', '');
        $this->onboarding_terms_agreement = Setting::get('onboarding_terms_agreement', '');
        $this->onboarding_terms_use = Setting::get('onboarding_terms_use', '');
        $this->onboarding_terms_ip = Setting::get('onboarding_terms_ip', '');
        $this->onboarding_terms_termination = Setting::get('onboarding_terms_termination', '');
        $this->onboarding_terms_liability = Setting::get('onboarding_terms_liability', '');
        $this->onboarding_terms_law = Setting::get('onboarding_terms_law', '');
        $this->onboarding_terms_changes = Setting::get('onboarding_terms_changes', '');
        $this->onboarding_terms_contact = Setting::get('onboarding_terms_contact', '');
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

    public function updatedUserReminderEnabled($value): void
    {
        $this->writeBoolSetting('user_reminder_enabled', $value, 'notification');
    }

    public function updatedProviderReminderEnabled($value): void
    {
        $this->writeBoolSetting('provider_reminder_enabled', $value, 'notification');
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
        $this->writeBoolSetting('push_notifications', $this->push_notifications, 'notification');
        $this->writeBoolSetting('email_notifications', $this->email_notifications, 'notification');
        $this->writeBoolSetting('sms_notifications', $this->sms_notifications, 'notification');
        $this->writeBoolSetting('user_reminder_enabled', $this->user_reminder_enabled, 'notification');
        $this->writeBoolSetting('provider_reminder_enabled', $this->provider_reminder_enabled, 'notification');
        $this->user_reminder_times = $this->normalizeReminderTimes($this->user_reminder_times);
        $this->provider_reminder_times = $this->normalizeReminderTimes($this->provider_reminder_times);

        Setting::set('user_reminder_times', json_encode(array_values($this->user_reminder_times)), 'notification');
        Setting::set('provider_reminder_times', json_encode(array_values($this->provider_reminder_times)), 'notification');
        Setting::set('user_reminder_messages', json_encode($this->sanitizeReminderMessagesMap($this->user_reminder_messages)), 'notification');
        Setting::set('provider_reminder_messages', json_encode($this->sanitizeReminderMessagesMap($this->provider_reminder_messages)), 'notification');
        
        $this->dispatch('showSweetAlert', 'success', 'Notification settings updated successfully!', 'Success');
    }

    public function editMessage(string $key)
    {
        $this->editingMessageValue = '';

        if (! preg_match('/^(user|provider):(15m|30m|45m)$/', $key, $m)) {
            return;
        }

        $this->editingMessageKey = $key;
        $map = $m[1] === 'provider' ? $this->provider_reminder_messages : $this->user_reminder_messages;
        $interval = $m[2];
        $this->editingMessageValue = $map[$interval] ?? '';
    }

    public function saveMessage()
    {
        $key = $this->editingMessageKey;
        $value = trim((string) $this->editingMessageValue);

        if (! $key || ! preg_match('/^(user|provider):(15m|30m|45m)$/', $key, $m)) {
            return;
        }

        $interval = $m[2];
        if ($m[1] === 'provider') {
            $this->provider_reminder_messages[$interval] = $value;
            Setting::set('provider_reminder_messages', json_encode($this->sanitizeReminderMessagesMap($this->provider_reminder_messages)), 'notification');
        } else {
            $this->user_reminder_messages[$interval] = $value;
            Setting::set('user_reminder_messages', json_encode($this->sanitizeReminderMessagesMap($this->user_reminder_messages)), 'notification');
        }

        $this->editingMessageKey = null;
        $this->editingMessageValue = '';
    }

    /**
     * @return list<array{key: string, label: string}>
     */
    public static function reminderIntervals(): array
    {
        return [
            ['key' => '15m', 'label' => '15 minutes before'],
            ['key' => '30m', 'label' => '30 minutes before'],
            ['key' => '45m', 'label' => '45 minutes before'],
        ];
    }

    public function defaultUserReminderText(string $intervalKey): string
    {
        $m = match ($intervalKey) {
            '30m' => '30',
            '45m' => '45',
            default => '15',
        };

        return "Your booked service will begin in {$m} minutes. Be available at your chosen location.";
    }

    public function defaultProviderReminderText(string $intervalKey): string
    {
        $m = match ($intervalKey) {
            '30m' => '30',
            '45m' => '45',
            default => '15',
        };

        return "You have an upcoming booking starting in {$m} minutes. Be available at the service location.";
    }

    /**
     * @param  mixed  $raw
     * @return list<string>
     */
    private function normalizeReminderTimes($raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $allowed = ['15m', '30m', '45m'];

        return array_values(array_unique(array_intersect($allowed, array_map('strval', array_values($raw)))));
    }

    /**
     * @return array<string, string>
     */
    private function loadReminderMessagesMap(string $audience): array
    {
        $prefix = $audience === 'provider' ? 'provider' : 'user';
        $decoded = json_decode(Setting::get("{$prefix}_reminder_messages", '{}'), true);
        if (! is_array($decoded)) {
            $decoded = [];
        }

        $hasNew = false;
        foreach (['15m', '30m', '45m'] as $k) {
            if (array_key_exists($k, $decoded)) {
                $hasNew = true;
                break;
            }
        }

        if (! $hasNew) {
            $legacy15 = (string) Setting::get("{$prefix}_reminder_message_15", '');
            $legacy60 = (string) Setting::get("{$prefix}_reminder_message_60", '');
            $legacy1d = (string) Setting::get("{$prefix}_reminder_message_1d", '');
            if ($legacy15 !== '' || $legacy60 !== '' || $legacy1d !== '') {
                $decoded['15m'] = $legacy15;
                $decoded['30m'] = $legacy60;
                $decoded['45m'] = $legacy1d;
            }
        }

        $out = [];
        foreach (['15m', '30m', '45m'] as $k) {
            $out[$k] = isset($decoded[$k]) ? trim((string) $decoded[$k]) : '';
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $map
     * @return array<string, string>
     */
    private function sanitizeReminderMessagesMap(array $map): array
    {
        $out = [];
        foreach (['15m', '30m', '45m'] as $k) {
            $out[$k] = isset($map[$k]) ? trim((string) $map[$k]) : '';
        }

        return $out;
    }

    private function readBoolSetting(string $key, bool $default): bool
    {
        $raw = Setting::get($key, null);
        if ($raw === null || $raw === '') {
            return $default;
        }

        return filter_var($raw, FILTER_VALIDATE_BOOLEAN);
    }

    private function writeBoolSetting(string $key, $value, string $group): void
    {
        $on = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        Setting::set($key, $on ? '1' : '0', $group);
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
            'terms_agreement' => $this->onboarding_terms_agreement,
            'terms_use' => $this->onboarding_terms_use,
            'terms_ip' => $this->onboarding_terms_ip,
            'terms_termination' => $this->onboarding_terms_termination,
            'terms_liability' => $this->onboarding_terms_liability,
            'terms_law' => $this->onboarding_terms_law,
            'terms_changes' => $this->onboarding_terms_changes,
            'terms_contact' => $this->onboarding_terms_contact,
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
            case 'terms_agreement':
                $this->onboarding_terms_agreement = $value;
                Setting::set('onboarding_terms_agreement', $value, 'onboarding');
                break;
            case 'terms_use':
                $this->onboarding_terms_use = $value;
                Setting::set('onboarding_terms_use', $value, 'onboarding');
                break;
            case 'terms_ip':
                $this->onboarding_terms_ip = $value;
                Setting::set('onboarding_terms_ip', $value, 'onboarding');
                break;
            case 'terms_termination':
                $this->onboarding_terms_termination = $value;
                Setting::set('onboarding_terms_termination', $value, 'onboarding');
                break;
            case 'terms_liability':
                $this->onboarding_terms_liability = $value;
                Setting::set('onboarding_terms_liability', $value, 'onboarding');
                break;
            case 'terms_law':
                $this->onboarding_terms_law = $value;
                Setting::set('onboarding_terms_law', $value, 'onboarding');
                break;
            case 'terms_changes':
                $this->onboarding_terms_changes = $value;
                Setting::set('onboarding_terms_changes', $value, 'onboarding');
                break;
            case 'terms_contact':
                $this->onboarding_terms_contact = $value;
                Setting::set('onboarding_terms_contact', $value, 'onboarding');
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
