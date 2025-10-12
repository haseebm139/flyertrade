<div>

    <livewire:admin.components.toolbar label="Booking" :show-add-button="false" />


    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="4">Service category <img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="1">Service User<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="1">Provider<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="2">Location <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status active">Active</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Cleaning <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/three.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>



                <td><span class="status active">Active</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/four.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status active">Active</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Electric work</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/five.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status active">Active</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status inactive">Inactive</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/seven.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status active">Active</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>

            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/five.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>$1200</td>


                <td><span class="status inactive">Inactive</span></td>
                <td class="viw-parent">
                    <button class="view-btn" onclick="openBookingModal()">
                        <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@if ($showFilterModal)
<div class="modal filter-theme-modals" style="display: flex;">
    <div class="modal-content filter-modal">
        <span class="close-modal" wire:click="closeFilterModal">&times;</span>
        <h3>Filter</h3>
        <label>Select Date</label>
        <div class="date-range">
            <div>
                <span>From:</span>
                <input type="date" class="form-input" wire:model="fromDate">
            </div>
            <div>
                <span>To:</span>
                <input type="date" class="form-input" wire:model="toDate">
            </div>
        </div>
         
    </div>
</div>
  @endif