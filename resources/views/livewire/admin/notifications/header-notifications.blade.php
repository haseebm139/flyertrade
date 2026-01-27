<div wire:poll.10s="loadNotifications">
    @if(count($groupedNotifications) > 0)
        @foreach($groupedNotifications as $group)
            <div class="notification_item_wrapper" onclick="handleGroupView(event, '{{ $group['type'] }}')" style="cursor: pointer;">
                <div class="notification-item {{ $group['has_unread'] ? 'unread' : '' }}" style="cursor: pointer; position: relative; display: flex; align-items: center;">
                    <img src="{{ $group['icon_url'] }}" alt="" style="border-radius: 0.521vw;">
                    <div class="notification-content">
                        <div class="notification-title">{{ $group['title'] }}</div>
                    </div>
                </div>
                <div class="notification_text_wrapper">
                    <div class="notification-text">
                        {{ $group['count'] }} {{ $group['count'] == 1 ? 'notification' : 'notifications' }}.
                    </div>
                    <div class="notification-view" data-modal="providerModal" onclick="event.stopPropagation(); handleGroupView(event, '{{ $group['type'] }}')">View</div>
                </div>
            </div>
        @endforeach
    @else
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <div class="notification-content" style="text-align: center; padding: 2vw;">
                    <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                        No notifications found.
                    </div>
                </div>
            </div>
        </div>
    @endif
    @can('Read Notifications')
    <div class="view_all_notification" style="text-align: left;">
        <a href="{{ route('notification.index') }}" style="color: #00796B; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.3vw;">
            View all notifications
            <span style="font-size: 1vw;">&#8250;</span>
        </a>
    </div>
    @endcan
</div>
