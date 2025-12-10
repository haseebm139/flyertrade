<div class="row ">

    @foreach ($stats as $stat)
        <div class=" col-md-3">
            <div class="dashboard-card">
                <div>
                    <h6>{{ $stat['label'] }}</h6>
                    <h2>{{ $stat['value'] ?? 0 }}</h2>
                </div>
                <div class="icon-box">
                    <img src="{{ asset($stat['icon']) }}" alt="icon">
                </div>
            </div>
        </div>
    @endforeach

</div>
