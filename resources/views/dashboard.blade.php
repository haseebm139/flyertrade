@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('content')


<style>
    table tr:nth-child(3) td {
    border: 0px solid #000;
}
.tabs-recently-added-users .nav-link{
    border: none;
}

</style>
    <!-- two-column area: left (finance + table) and right (recent activities) -->
    <div class="row g-3">
        <!-- MAIN LEFT COLUMN (8/12) -->
        <div class="col-lg-9">
            <!-- top stat cards -->

            <div class="row ">
                @foreach ($stats as $stat)
                    <div class="col-md-3 col-sm-6 col-6">
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

            <!-- finances & disputes row -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <livewire:admin.finance-summary />
                </div>

                <div class="col-md-6 mb-4">
                    <div class="recent-dispute-card w-100   mt-4">
                        <!-- Header -->
                        <div class="recent-dispute-header">
                            <h5>Recent dispute</h5>
                            @can('Read Disputes')
                                <a href="{{ route('dispute.index') }}">View all</a>
                            @endcan
                        </div>

                        <!-- Table -->
                        <div class="table-responsive1">
                            <table class="table mb-0" border="0">
                                <thead>
                                    <tr>
                                        <th width="50%">Affected user</th>
                                        <th width="50%">Dispute issue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentDisputes as $dispute)
                                        <tr>
                                            <td>
                                                <div class="user-info name-2">
                                                    <img src="{{ asset($dispute->user?->avatar ?? 'assets/images/icons/person.svg') }}"
                                                        alt="User">
                                                    {{ $dispute->user?->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::limit($dispute->message ?? '', 80) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No disputes found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <!-- Recently added users DataTable -->
                    <div class="card user-table-card h-100">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Recently added users</h5>
                            @canany(['Read Users', 'Read Providers'])
                                <a class="dashboard-view-all"
                                    href="{{ route('user-management.service.users.index') }}"
                                    style="font-weight:400,color:#1b1b1b ,font-size:0.875vw">
                                    View all
                                </a>
                            @endcanany
                        </div>
                        <nav>
                            <div class="nav nav-tabs tabs-recently-added-users" id="nav-tab" role="tablist">
                                <button class="nav-link tab active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true"
                                    data-view-url="{{ route('user-management.service.users.index') }}">
                                    Service users
                                </button>
                                <button class="nav-link tab" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false"
                                    data-view-url="{{ route('user-management.service.providers.index') }}">
                                    Service providers
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content d-block" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Name</th>
                                                <th>Home address</th>
                                                <th>Phone number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentUsers as $user)
                                                <tr>
                                                    <td class="user-data asq">{{ $user->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($user->avatar ?? 'assets/images/avatar/default.png') }}"
                                                                class="rounded-circle me-2" alt="User">
                                                            <div>
                                                                <div class="fw-semibold name">{{ $user->name ?? 'N/A' }}</div>
                                                                <small
                                                                    class="text-muted name-2">{{ $user->email ?? '-' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="user-data asq">{{ $user->address ?? '-' }}</td>
                                                    <td class="user-data asq">{{ $user->phone ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">No users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Name</th>
                                                <th>Home address</th>
                                                <th>Phone number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentProviders as $user)
                                                <tr>
                                                    <td class="user-data asq">{{ $user->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($user->avatar ?? 'assets/images/avatar/default.png') }}"
                                                                class="rounded-circle me-2" alt="User">
                                                            <div>
                                                                <div class="fw-semibold name">{{ $user->name ?? 'N/A' }}</div>
                                                                <small
                                                                    class="text-muted name-2">{{ $user->email ?? '-' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="user-data asq">{{ $user->address ?? '-' }}</td>
                                                    <td class="user-data asq">{{ $user->phone ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">No providers found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


        </div>

        <!-- RIGHT COLUMN (4/12): recent activities -->
        <div class="col-lg-3">
            <div class="card recent-activities">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Recent activities</h5>
                    </div>

                    <!-- Dynamic Activities -->
                    <livewire:admin.recent-activities />
                </div>
            </div>
        </div>

    </div> <!-- end main row -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const viewAllLink = document.querySelector('.dashboard-view-all');
            const tabs = document.querySelectorAll('.tabs-recently-added-users [data-bs-toggle="tab"]');

            if (!viewAllLink || tabs.length === 0) {
                return;
            }

            const updateLink = (tab) => {
                const url = tab?.dataset?.viewUrl;
                if (url) {
                    viewAllLink.setAttribute('href', url);
                }
            };

            const activeTab = document.querySelector('.tabs-recently-added-users .nav-link.active');
            if (activeTab) {
                updateLink(activeTab);
            }

            tabs.forEach((tab) => {
                tab.addEventListener('shown.bs.tab', (event) => {
                    updateLink(event.target);
                });
            });
        });
    </script>
@endsection
