<div wire:poll.30s="loadNotifications">
    <!-- tabs-section -->
    <div class="tabs-section">
        <div class="tab {{ $category === 'all' ? 'active' : '' }}" 
             wire:click="switchCategory('all')" 
             style="cursor: pointer; position: relative;">
            All
            @if(isset($categoryUnreadCounts['all']) && $categoryUnreadCounts['all'] > 0)
                <span style="background: #dc3545; color: white; border-radius: 50%; padding: 0.1vw 0.3vw; font-size: 0.7vw; margin-left: 0.3vw;">
                    {{ $categoryUnreadCounts['all'] }}
                </span>
            @endif
        </div>
        <div class="tab {{ $category === 'reviews' ? 'active' : '' }}" 
             wire:click="switchCategory('reviews')" 
             style="cursor: pointer; position: relative;">
            Reviews
            @if(isset($categoryUnreadCounts['reviews']) && $categoryUnreadCounts['reviews'] > 0)
                <span style="background: #dc3545; color: white; border-radius: 50%; padding: 0.1vw 0.3vw; font-size: 0.7vw; margin-left: 0.3vw;">
                    {{ $categoryUnreadCounts['reviews'] }}
                </span>
            @endif
        </div>
        <div class="tab {{ $category === 'bookings' ? 'active' : '' }}" 
             wire:click="switchCategory('bookings')" 
             style="cursor: pointer; position: relative;">
            Bookings
            @if(isset($categoryUnreadCounts['bookings']) && $categoryUnreadCounts['bookings'] > 0)
                <span style="background: #dc3545; color: white; border-radius: 50%; padding: 0.1vw 0.3vw; font-size: 0.7vw; margin-left: 0.3vw;">
                    {{ $categoryUnreadCounts['bookings'] }}
                </span>
            @endif
        </div>
        <div class="tab {{ $category === 'transactions' ? 'active' : '' }}" 
             wire:click="switchCategory('transactions')" 
             style="cursor: pointer; position: relative;">
            Transactions
            @if(isset($categoryUnreadCounts['transactions']) && $categoryUnreadCounts['transactions'] > 0)
                <span style="background: #dc3545; color: white; border-radius: 50%; padding: 0.1vw 0.3vw; font-size: 0.7vw; margin-left: 0.3vw;">
                    {{ $categoryUnreadCounts['transactions'] }}
                </span>
            @endif
        </div>
        <div class="tab {{ $category === 'admin_actions' ? 'active' : '' }}" 
             wire:click="switchCategory('admin_actions')" 
             style="cursor: pointer; position: relative;">
            Admin actions
            @if(isset($categoryUnreadCounts['admin_actions']) && $categoryUnreadCounts['admin_actions'] > 0)
                <span style="background: #dc3545; color: white; border-radius: 50%; padding: 0.1vw 0.3vw; font-size: 0.7vw; margin-left: 0.3vw;">
                    {{ $categoryUnreadCounts['admin_actions'] }}
                </span>
            @endif
        </div>
    </div>

    <!-- notifications content -->
    <div class="tab-content active" style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
        @foreach($groupedNotifications as $group)
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;margin-top: {{ $loop->first ? '0' : '1.5vw' }};" class="profile-heading">{{ $group['group'] }}</h3>
        <div class="profile-details">
            @forelse($group['notifications'] as $notification)
                <div class="notification_item_wrapper">
                    @if($notification['action_url'])
                        <a href="{{ $notification['action_url'] }}" 
                           onclick="if (!this.dataset.marked) { this.dataset.marked = '1'; @this.call('markAsRead', {{ $notification['id'] }}); }"
                           style="text-decoration: none; color: inherit; display: block;">
                    @endif
                    <div class="notification-item {{ $notification['read_at'] ? '' : 'unread' }}" 
                         @if(!$notification['action_url']) wire:click="markAsRead({{ $notification['id'] }})" @endif
                         style="cursor: pointer; position: relative; display: flex; align-items: center;">
                        <img src="{{ $notification['icon_url'] }}" alt="" style="border-radius: 0.521vw;">
                        <div class="notification-content" style="flex: 1;">
                            <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">
                                {{ $notification['title'] }}
                            </div>
                            <div class="notification_text_wrapper">
                                <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                                    {{ $notification['message'] }}
                                </div>
                                <div class="notification-view" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">
                                    {{ $notification['time_ago'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($notification['action_url'])
                        </a>
                    @endif
                </div>
            @empty
                <div class="notification_item_wrapper">
                    <div class="notification-item">
                        <div class="notification-content" style="text-align: center; padding: 2vw;">
                            <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                                No notifications found.
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    @endforeach

    @if(empty($groupedNotifications))
        <div class="profile-details">
            <div class="notification_item_wrapper">
                <div class="notification-item">
                    <div class="notification-content" style="text-align: center; padding: 2vw;">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            No notifications available.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($hasMore)
        <div id="load-more-trigger" style="height: 20px;"></div>
        @if($loading)
            <div style="text-align: center; padding: 1vw;">
                <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                    Loading more notifications...
                </div>
            </div>
        @endif
    @endif
    </div>

    <style>
        .notification-item.unread {
            /* background-color: #f8f9fa; */
            border-left: 3px solid #007bff;
        }
        .notification-item:hover {
            background-color: #f0f0f0;
        }
        .notification-item {
            display: flex;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreTrigger = document.getElementById('load-more-trigger');
            if (!loadMoreTrigger) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        @this.call('loadMore');
                    }
                });
            }, {
                rootMargin: '100px'
            });

            observer.observe(loadMoreTrigger);

            // Cleanup on component update
            Livewire.hook('morph.updated', () => {
                const newTrigger = document.getElementById('load-more-trigger');
                if (newTrigger && !newTrigger.hasAttribute('data-observed')) {
                    newTrigger.setAttribute('data-observed', 'true');
                    observer.observe(newTrigger);
                }
            });
        });
    </script>
</div>
