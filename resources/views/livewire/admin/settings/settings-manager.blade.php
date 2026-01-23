<div>
    <div class="row">
        <div class="col-md-2">
            <div class="tabs-vertical-wrapper">
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'general' ? 'active' : '' }}"
                    wire:click="switchTab('general')">General</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'financial' ? 'active' : '' }}"
                    wire:click="switchTab('financial')">Financial</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'notification' ? 'active' : '' }}"
                    wire:click="switchTab('notification')">Notification</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'system' ? 'active' : '' }}"
                    wire:click="switchTab('system')">System Log & Audits</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'admin' ? 'active' : '' }}"
                    wire:click="switchTab('admin')">Admin Management</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'onboarding' ? 'active' : '' }}"
                    wire:click="switchTab('onboarding')">Onboarding</div>
                <div class="tab roles-permission-theme-tabs {{ $activeTab === 'content' ? 'active' : '' }}"
                    wire:click="switchTab('content')">Content Moderation</div>
            </div>
        </div>
        <div class="col-md-10">
            @if ($activeTab === 'general')
                <div id="general" class="tab-content active">
                    <h3>General</h3>
                    <p>General information and settings content goes here.</p>
                </div>
            @endif

            @if ($activeTab === 'financial')
                <div id="financial" class="tab-content active">
                    @php
                        $currencyOptions = collect($countries)
                            ->filter(fn($c) => !empty($c->currency_code))
                            ->unique(fn($c) => strtolower($c->currency_code))
                            ->values();
                        $selectedCurrency = $currencyOptions->first(
                            fn($c) => strtolower($c->currency_code) === strtolower($currency ?? '')
                        );
                    @endphp
                    <div class="setting-wrapper">
                        <div class="charge-col">
                            <label class="charge-label">Select Country</label>
                            @php
                                $selectedCountry = $countries->firstWhere('id', $country_id);
                            @endphp
                            <div class="country-dropdown">
                                <button type="button" class="country-select" onclick="toggleCountryDropdown(this)">
                                    <span class="country-selected">
                                        @if ($selectedCountry && $selectedCountry->flag_url)
                                            <img src="{{ asset($selectedCountry->flag_url) }}" alt=""
                                                class="country-flag">
                                        @endif
                                        <span>{{ $selectedCountry->name ?? 'Select Country' }}</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-down country-caret"></i>
                                </button>
                                <div class="country-list">
                                    <div class="country-search">
                                        <input type="text" class="country-search-input" placeholder="Search country"
                                            oninput="filterCountryList(this)">
                                    </div>
                                    <button type="button" class="country-item" wire:click="$set('country_id', '')"
                                        onclick="closeCountryDropdown(this)">
                                        <span>Select Country</span>
                                    </button>
                                    @foreach ($countries as $country)
                                        <button type="button"
                                            class="country-item {{ (string) $country->id === (string) $country_id ? 'active' : '' }}"
                                            data-iso="{{ strtolower($country->iso2 ?? '') }}"
                                            wire:click="$set('country_id', {{ $country->id }})"
                                            onclick="closeCountryDropdown(this)">
                                            @if ($country->flag_url)
                                                <img src="{{ asset($country->flag_url) }}" alt=""
                                                    class="country-flag"
                                                    style="width: 1.042vw; height: 1.042vw; border-radius: 50%;">
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
                            <div class="currency-dropdown">
                                <button type="button" class="currency-select" onclick="toggleCurrencyDropdown(this)">
                                    <span class="currency-selected">
                                        @if ($selectedCurrency)
                                            {{-- <span class="currency-code">{{ strtoupper($selectedCurrency->currency_code) }}</span> --}}
                                            <span class="currency-name">{{ $selectedCurrency->currency_name }}</span>
                                            @if (!empty($selectedCurrency->currency_symbol))
                                                <span class="currency-symbol">({{ $selectedCurrency->currency_symbol }})</span>
                                            @endif
                                        @else
                                            <span>Select currency</span>
                                        @endif
                                    </span>
                                    <i class="fa-solid fa-chevron-down currency-caret"></i>
                                </button>
                                <div class="currency-list">
                                    <div class="currency-search">
                                        <input type="text" class="currency-search-input" placeholder="Search currency"
                                            oninput="filterCurrencyList(this)">
                                    </div>
                                    @foreach ($currencyOptions as $currencyItem)
                                        <button type="button"
                                            class="currency-item {{ strtolower($currencyItem->currency_code) === strtolower($currency ?? '') ? 'active' : '' }}"
                                            data-code="{{ strtolower($currencyItem->currency_code) }}"
                                            wire:click="$set('currency', '{{ $currencyItem->currency_code }}')"
                                            onclick="closeCurrencyDropdown(this)">
                                            {{-- <span class="currency-code">{{ strtoupper($currencyItem->currency_code) }}</span> --}}
                                            <span class="currency-name">{{ $currencyItem->currency_name }}</span>
                                            @if (!empty($currencyItem->currency_symbol))
                                                <span class="currency-symbol">({{ $currencyItem->currency_symbol }})</span>
                                            @endif
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
                            <label class="charge-label">Commission Fee (%)</label>
                            <input type="number" wire:model="commission_fee" class="charge-input" step="1" min="0" max="100"
                                placeholder="Enter commission fee" />
                            @error('commission_fee')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br />
                    <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                        <button type="button" class="cancel-btn" wire:click="loadSettings">Cancel</button>
                        <button type="button" class="submit-btn" wire:click="saveFinancial">Save
                            Changes</button>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'notification')
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

            @if ($activeTab === 'system')
                <div id="system" class="tab-content active">
                    <h3>System Log & Audits</h3>
                    <p>Audit trails and system logs will be displayed here.</p>
                </div>
            @endif

            @if ($activeTab === 'admin')
                <div id="admin" class="tab-content active">
                    <h3>Admin Management</h3>
                    <p>Manage admin users, roles, and permissions here.</p>
                </div>
            @endif

            @if ($activeTab === 'onboarding')
                <div id="onboarding" class="tab-content active">
                    <h3>Onboarding</h3>
                    <p>Onboarding settings content goes here.</p>
                </div>
            @endif

            @if ($activeTab === 'content')
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
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 14px;
            justify-content: space-between;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .country-select:hover {
            border-color: #d1d5db;
        }
        .country-select:focus-visible {
            outline: none;
            border-color: #0b63c9;
            box-shadow: 0 0 0 3px rgba(11, 99, 201, 0.15);
        }

        .country-selected {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #111827;
        }

        .country-select .country-flag {
            width: 1.042vw;
            height: 1.042vw;
            object-fit: cover;
            border-radius: 50%;
        }

        .country-list {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            max-height: 260px;
            overflow-y: auto;
            display: none;
            z-index: 10;
            box-shadow: 0 10px 20px rgba(16, 24, 40, 0.08);
        }
        .country-search {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .country-search-input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .country-search-input:focus {
            outline: none;
            border-color: #0b63c9;
            box-shadow: 0 0 0 2px rgba(11, 99, 201, 0.12);
        }

        .country-dropdown.open .country-list {
            display: block;
        }

        .country-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #fff;
            border: 0;
            text-align: left;
            font-size: 14px;
            transition: background 0.15s ease;
        }

        .country-item:hover {
            background: #f5f5f5;
        }
        .country-item.active {
            background: rgba(11, 99, 201, 0.08);
        }

        .country-caret {
            color: #9ca3af;
            font-size: 12px;
        }

        .currency-dropdown {
            position: relative;
        }
        .currency-select {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 14px;
            justify-content: space-between;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .currency-select:hover {
            border-color: #d1d5db;
        }
        .currency-select:focus-visible {
            outline: none;
            border-color: #0b63c9;
            box-shadow: 0 0 0 3px rgba(11, 99, 201, 0.15);
        }
        .currency-selected {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #111827;
        }
        .currency-code {
            font-weight: 600;
            letter-spacing: 0.4px;
        }
        .currency-name {
            color: #374151;
        }
        .currency-symbol {
            color: #6b7280;
        }
        .currency-list {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            max-height: 260px;
            overflow-y: auto;
            display: none;
            z-index: 10;
            box-shadow: 0 10px 20px rgba(16, 24, 40, 0.08);
        }
        .currency-dropdown.open .currency-list {
            display: block;
        }
        .currency-search {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .currency-search-input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .currency-search-input:focus {
            outline: none;
            border-color: #0b63c9;
            box-shadow: 0 0 0 2px rgba(11, 99, 201, 0.12);
        }
        .currency-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #fff;
            border: 0;
            text-align: left;
            font-size: 14px;
            transition: background 0.15s ease;
        }
        .currency-item:hover {
            background: #f5f5f5;
        }
        .currency-item.active {
            background: rgba(11, 99, 201, 0.08);
        }
        .currency-caret {
            color: #9ca3af;
            font-size: 12px;
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

        function filterCountryList(input) {
            const wrapper = input.closest('.country-list');
            if (!wrapper) return;
            const search = input.value.toLowerCase().trim();
            const items = wrapper.querySelectorAll('.country-item');
            if (!search) {
                items.forEach(item => item.style.display = '');
                return;
            }

            const searchParts = search.split(/\s+/).filter(Boolean);
            const searchNoSpace = search.replace(/\s+/g, '');

            items.forEach(item => {
                const text = item.textContent.toLowerCase().trim();
                const textNoSpace = text.replace(/\s+/g, '');
                const iso = (item.dataset.iso || '').toLowerCase();

                const matchesIso = search.length <= 3 && iso && iso.startsWith(search);
                const matchesText = text.includes(search) || textNoSpace.includes(searchNoSpace);
                const matchesTokens = searchParts.every(part => text.includes(part));

                item.style.display = (matchesIso || matchesText || matchesTokens) ? '' : 'none';
            });
        }

        function toggleCurrencyDropdown(button) {
            const wrapper = button.closest('.currency-dropdown');
            if (!wrapper) return;
            const isOpen = wrapper.classList.contains('open');
            document.querySelectorAll('.currency-dropdown.open').forEach(el => el.classList.remove('open'));
            if (!isOpen) {
                wrapper.classList.add('open');
            }
        }

        function closeCurrencyDropdown(button) {
            const wrapper = button.closest('.currency-dropdown');
            if (wrapper) {
                wrapper.classList.remove('open');
            }
        }

        function filterCurrencyList(input) {
            const wrapper = input.closest('.currency-list');
            if (!wrapper) return;
            const search = input.value.toLowerCase().trim();
            const items = wrapper.querySelectorAll('.currency-item');
            if (!search) {
                items.forEach(item => item.style.display = '');
                return;
            }
            const searchParts = search.split(/\s+/).filter(Boolean);
            const searchNoSpace = search.replace(/\s+/g, '');
            items.forEach(item => {
                const text = item.textContent.toLowerCase().trim();
                const textNoSpace = text.replace(/\s+/g, '');
                const code = (item.dataset.code || '').toLowerCase();
                const matchesCode = search.length <= 3 && code && code.startsWith(search);
                const matchesText = text.includes(search) || textNoSpace.includes(searchNoSpace);
                const matchesTokens = searchParts.every(part => text.includes(part));
                item.style.display = (matchesCode || matchesText || matchesTokens) ? '' : 'none';
            });
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.country-dropdown')) {
                document.querySelectorAll('.country-dropdown.open').forEach(el => el.classList.remove('open'));
            }
            if (!e.target.closest('.currency-dropdown')) {
                document.querySelectorAll('.currency-dropdown.open').forEach(el => el.classList.remove('open'));
            }
        });
    </script>
</div>
