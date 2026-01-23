<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Models\Country;
use Livewire\Component;

class SettingsManager extends Component
{
    public $activeTab = 'financial';
    
    // Financial Settings
    public $country_id;
    public $currency;
    public $commission_fee;
    
    // Notification Settings
    public $push_notifications;
    public $email_notifications;
    public $sms_notifications;
    
    public function mount()
    {
        $this->loadSettings();
    }
    
    public function loadSettings()
    {
        $this->country_id = Setting::get('country_id');
        $this->currency = Setting::get('currency', 'USD');
        $this->commission_fee = Setting::get('commission_fee', 0);

        $this->push_notifications = (bool) Setting::get('push_notifications', true);
        $this->email_notifications = (bool) Setting::get('email_notifications', true);
        $this->sms_notifications = (bool) Setting::get('sms_notifications', false);
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function saveFinancial()
    {
        Setting::set('country_id', $this->country_id, 'financial');
        Setting::set('currency', $this->currency, 'financial');
        Setting::set('commission_fee', $this->commission_fee, 'financial');
        
        $this->dispatch('showSweetAlert', 'success', 'Financial settings updated successfully!', 'Success');
    }

    public function saveNotifications()
    {
        Setting::set('push_notifications', $this->push_notifications, 'notification');
        Setting::set('email_notifications', $this->email_notifications, 'notification');
        Setting::set('sms_notifications', $this->sms_notifications, 'notification');
        
        $this->dispatch('showSweetAlert', 'success', 'Notification settings updated successfully!', 'Success');
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
