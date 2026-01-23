<div>
    <div class="row">
        <div class="col-md-2">
            <div class="tabs-vertical-wrapper">
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'general' ? 'active' : '' }}" wire:click="switchTab('general')">General</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'financial' ? 'active' : '' }}" wire:click="switchTab('financial')">Financial</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'notification' ? 'active' : '' }}" wire:click="switchTab('notification')">Notification</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'system' ? 'active' : '' }}" wire:click="switchTab('system')">System Log & Audits</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'admin' ? 'active' : '' }}" wire:click="switchTab('admin')">Admin Management</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'onboarding' ? 'active' : '' }}" wire:click="switchTab('onboarding')">Onboarding</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'content' ? 'active' : '' }}" wire:click="switchTab('content')">Content Moderation</div>
            </div>
        </div>
        <div class="col-md-10">
            @if($activeTab === 'general')
                <div id="general" class="tab-content active">
                    <h3>General</h3>
                    <p>General information and settings content goes here.</p>
                </div>
            @endif

            @if($activeTab === 'financial')
                <div id="financial" class="tab-content active">
                    <div class="setting-wrapper">
                        <div class="charge-col">
                            <label class="charge-label">Select Country</label>
                            @php
                                $selectedCountry = $countries->firstWhere('id', $country_id);
                            @endphp
                            <div class="country-dropdown">
                                <button type="button" class="country-select" onclick="toggleCountryDropdown(this)">
                                    @if($selectedCountry && $selectedCountry->flag_url)
                                        <img src="{{ asset($selectedCountry->flag_url) }}" alt="" class="country-flag">
                                    @endif
                                    <span>{{ $selectedCountry->name ?? 'Select Country' }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="country-list">
                                    <button type="button" class="country-item"
                                        wire:click="$set('country_id', '')" onclick="closeCountryDropdown(this)">
                                        <span>Select Country</span>
                                    </button>
                                    @foreach($countries as $country)
                                        <button type="button" class="country-item"
                                            wire:click="$set('country_id', {{ $country->id }})" onclick="closeCountryDropdown(this)">
                                            @if($country->flag_url)
                                                <img src="{{ asset($country->flag_url) }}" alt="" class="country-flag">
                                            @endif
                                            <span>{{ $country->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="setting-wrapper">
                        <div class="charge-col">
                            <label class="charge-label">Select currency</label>
                            <select wire:model="currency" class="charge-input" style="width: 100%;">
                                <option value="USD">USD - Dollars</option>
                                <option value="AED">AED - Dirham</option>
                                <option value="GBP">GBP - Pound</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="setting-wrapper">
                        <div class="charge-col">
                            <label class="charge-label">Commission Fee (%)</label>
                            <input type="number" wire:model="commission_fee" class="charge-input" placeholder="Enter commission fee" />
                        </div>
                    </div>
                    <br />
                    <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                        <button type="button" class="cancel-btn" wire:click="loadSettings">Cancel</button>
                        <button type="button" class="submit-btn" wire:click="saveFinancial">Save Changes</button>
                    </div>
                </div>
            @endif

            @if($activeTab === 'notification')
                <div id="notification" class="tab-content active">
                    <div class="setting-wrapper">
                        <h2 style="font-size: 1.042vw;">Global notification toggle</h2>
                        <div class="permission-item">
                            <span>Push Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model.live="push_notifications" />
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="permission-item">
                            <span>Email Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model.live="email_notifications" />
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="permission-item">
                            <span>SMS Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model.live="sms_notifications" />
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    <br />
                    <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                        <button type="button" class="cancel-btn" wire:click="loadSettings">Cancel</button>
                        <button type="button" class="submit-btn" wire:click="saveNotifications">Save Changes</button>
                    </div>
                </div>
            @endif

            @if($activeTab === 'system')
                <div id="system" class="tab-content active">
                    <h3>System Log & Audits</h3>
                    <p>Audit trails and system logs will be displayed here.</p>
                </div>
            @endif

            @if($activeTab === 'admin')
                <div id="admin" class="tab-content active">
                    <h3>Admin Management</h3>
                    <p>Manage admin users, roles, and permissions here.</p>
                </div>
            @endif

            @if($activeTab === 'onboarding')
                <div id="onboarding" class="tab-content active">
                    <h3>Onboarding</h3>
                    <p>Onboarding settings content goes here.</p>
                </div>
            @endif

            @if($activeTab === 'content')
                <div id="content" class="tab-content active">
                    <h3>Content Moderation</h3>
                    <p>Moderate user content and submissions in this section.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .country-dropdown {
            position: relative;
        }
        .country-select {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 0.6vw;
            border-radius: 0.5vw;
            border: 0.1vw solid #ddd;
            background: #fff;
            font-size: 1vw;
            justify-content: space-between;
        }
        .country-select .country-flag {
            width: 20px;
            height: 14px;
            object-fit: cover;
            border-radius: 2px;
        }
        .country-list {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 0.1vw solid #ddd;
            border-radius: 0.5vw;
            max-height: 240px;
            overflow-y: auto;
            display: none;
            z-index: 10;
        }
        .country-dropdown.open .country-list {
            display: block;
        }
        .country-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #fff;
            border: 0;
            text-align: left;
            font-size: 0.95vw;
        }
        .country-item:hover {
            background: #f5f5f5;
        }
    </style>

    <script>
        function toggleCountryDropdown(button) {
            const wrapper = button.closest('.country-dropdown');
            if (!wrapper) return;
            const isOpen = wrapper.classList.contains('open');
            document.querySelectorAll('.country-dropdown.open').forEach(el => el.classList.remove('open'));
            if (!isOpen) {
                wrapper.classList.add('open');
            }
        }

        function closeCountryDropdown(button) {
            const wrapper = button.closest('.country-dropdown');
            if (wrapper) {
                wrapper.classList.remove('open');
            }
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.country-dropdown')) {
                document.querySelectorAll('.country-dropdown.open').forEach(el => el.classList.remove('open'));
            }
        });
    </script>
</div>
