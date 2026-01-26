<div wire:poll.30s="loadActivities">
    @if (count($activities) > 0)
        @foreach ($activities as $activity)
            <div class="activity-item">
                <div class="activity-icon {{ $activity['bg_color'] }}">
                    <img src="{{ $activity['icon_url'] }}" alt="" class="icon-boxs">
                </div>
                <div class="activity-text">
                    <p class="title">{{ $activity['title'] }}:
                        <span>{{ $activity['message'] }}</span>
                    </p>
                    <small>{{ $activity['time_ago'] }}</small>
                </div>
            </div>
        @endforeach
    @else
        <div class="activity-item">
            <div class="activity-text">
                <p class="title">No recent activities</p>
                <small>Activities will appear here</small>
            </div>
        </div>
    @endif
</div>
