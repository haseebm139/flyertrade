<div class="finances-card mt-4">
    <div class="finances-header">
        <h5 class="card-title ">Finances</h5>
        <x-custom-select-livewire
            name="month"
            :options="$monthOptions"
            :selected="$month"
            wireModel="month"
            placeholder="Select month"
            class="form-select-sm"
            style="width: 25%; padding: 0.2vw 0.4vw 0.2vw 0.4vw;" />
    </div>

    <div class="finances-progress">
        <div class="progress-bar-custom progress-green"></div>
        <div class="progress-bar-custom progress-brown"></div>
    </div>

    <div class="finance-stats">
        <div class="stat-box">
            <div class="stat-title">
                <span class="revenue-dot"></span> Total revenue
            </div>
            <div class="stat-value">
                ${{ number_format($financeSummary['total_revenue'] ?? 0, 2) }}
                <span class="stat-up"><img src="{{ asset('assets/images/icons/value-high.svg') }}"
                        class="img-log" alt=""></span>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-title">
                <span class="payout-dot"></span> Total payout
            </div>
            <div class="stat-value">
                ${{ number_format($financeSummary['total_payout'] ?? 0, 2) }}
                <span class="stat-down"><img src="{{ asset('assets/images/icons/value-down.svg') }}"
                        class="img-log" alt=""></span>
            </div>
        </div>
    </div>
</div>
