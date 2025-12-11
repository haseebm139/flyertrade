@extends('admin.layouts.app')

@section('title', 'Settings')
@section('header', 'Settings')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js"></script>
<style>
    #country_selector {
        cursor: pointer;
        background-color: #fff;
    }
</style>

    <div class="row">
        <div class="col-md-2">
            <div class="tabs-vertical-wrapper">
                <div class="tab roles-permission-theme-tabs" data-target="general">General</div>
                <div class="tab roles-permission-theme-tabs" data-target="financial">Financial</div>
                <div class="tab roles-permission-theme-tabs active" data-target="notification">Notification</div>
                <div class="tab roles-permission-theme-tabs" data-target="system">System Log & Audits</div>
                <div class="tab roles-permission-theme-tabs" data-target="admin">Admin Management</div>
                <div class="tab roles-permission-theme-tabs" data-target="onboarding">Onboarding</div>
                <div class="tab roles-permission-theme-tabs" data-target="content">Content Moderation</div>
            </div>
        </div>
        <div class="col-md-10">
            <div id="general" class="tab-content">
                <h3>General</h3>
                <p>General information and settings content goes here.</p>
            </div>
            <div id="financial" class="tab-content">
                <div class="setting-wrapper">
                    <div class="charge-col">
                        <label class="charge-label">Select Country</label>
                        <input id="country_selector" style="width:100%"  class="charge-input" type="tel" name="country" class="form-control" readonly>


                        <!-- <select class="charge-input" style="width: auto;">
                            <option>United States</option>
                            <option>England</option>
                            <option>Australia</option>
                        </select> -->
                    </div>
                </div>
                <br />
                <br />
                <div class="setting-wrapper">
                    <div class="charge-col">
                        <label class="charge-label">Select currency</label>
                        <select class="charge-input" style="width: auto;">
                            <option>Dollars</option>
                            <option>Dollars</option>
                            <option>Dollars</option>
                        </select>
                    </div>
                </div>
                <br />
                <br />
                <style>
                    /* Chrome, Safari, Edge, Opera */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

                </style>
                <div class="setting-wrapper">
                    <div class="charge-col">
                        <label class="charge-label">Commission Fee</label>
                        <input type="number" class="charge-input" placeholder="Enter commission fee" />
                    </div>
                </div>
                <br />
                <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="submit-btn">Save Changes</button>
                </div>
            </div>
            <div id="notification" class="tab-content message active">
                <div class="setting-wrapper">
                    <h2 style="font-size: 1.042vw;">Global notification toggle</h2>
                    <div class="permission-item">
                        <span  >Can manage dashboard</span>
                        <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="permission-item">
                        <span  >Can manage dashboard</span>
                        <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="permission-item">
                        <span  >Can manage dashboard</span>
                        <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <br />
                <div class="setting-wrapper">
                    <h2 style="font-size: 1.042vw;">Specific notification reminders</h2>
                    <br />
                    <h4 style="font-size: 1vw;">User Service</h4>
                    <div class="permission-item">
                        <span>Upcoming service reminder</span>
                        <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                       <div class="permission-item">
                        <span>Reminder timing</span>
                        <!-- <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label> -->
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >15 minutes before</span>
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >30 minutes before</span>
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >45 minutes before</span>
                    </div>
                    <br />
                    <h4 style="font-size: 1vw;">Reminder Content</h4>
                    <div
                        style="border: 1px solid #ccc; border-radius: 0.4vw; padding: 0.8vw; background-color: #fff; width: 100%; font-family: sans-serif;">
                        <!-- Row 1 -->
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">15 minutes
                                    before&nbsp;</span>
                                ‘’Your booked service will begin in 15 minutes. Be available at your chosen location.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                        <!-- Row 2 -->
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">1 hour before&nbsp;</span>
                                ‘’Reminder: your artist will arrive in approximately 1 hour.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                        <!-- Row 3 -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">1 day before&nbsp;</span>
                                ‘’Don’t forget your appointment tomorrow. Prepare your setup and be on time.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
                <br />
                <div class="setting-wrapper">
                    <h2 style="font-size: 1.042vw;">Specific notification reminders</h2>
                    <br />
                    <h4 style="font-size: 1vw;">Service Providers</h4>
                    <div class="permission-item">
                        <span >Upcoming service reminder</span>
                        <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                            <div class="permission-item">
                        <span>Reminder timing</span>
                        <!-- <label class="toggle-switch">
                            <input type="checkbox" />
                            <span class="slider"></span>
                        </label> -->
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >15 minutes before</span>
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >30 minutes before</span>
                    </div>
                    <div class="permission-items">
                        <input type="checkbox" />
                        <span >45 minutes before</span>
                    </div>
                    <br />
                    <h4 style="font-size: 1vw;">Reminder Content</h4>
                    <div
                        style="border: 1px solid #ccc; border-radius: 0.4vw; padding: 0.8vw; background-color: #fff; width: 100%; font-family: sans-serif;">
                        <!-- Row 1 -->
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">15 minutes
                                    before&nbsp;</span>
                                ‘’Your booked service will begin in 15 minutes. Be available at your chosen location.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                        <!-- Row 2 -->
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">1 hour
                                    before&nbsp;</span>
                                ‘’Reminder: your artist will arrive in approximately 1 hour.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                        <!-- Row 3 -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                            <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                                <span style="font-size: 0.9vw; font-weight: 300; color: #a7a5a5;">1 day before&nbsp;</span>
                                ‘’Don’t forget your appointment tomorrow. Prepare your setup and be on time.’’
                            </p>
                            <button class="view-btn" style="font-weight: 600;">
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
                <br />
                <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="submit-btn">Save Changes</button>
                </div>
            </div>
            <div id="system" class="tab-content">
                <h3>System Log & Audits</h3>
                <p>Audit trails and system logs will be displayed here.</p>
            </div>
            <div id="admin" class="tab-content">
                <h3>Admin Management</h3>
                <p>Manage admin users, roles, and permissions here.</p>
            </div>
            <div id="onboarding" class="tab-content">
                <div class="setting-wrapper">
                    <h2 style="font-size: 1.042vw;">Privacy policy</h2>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                        <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                            Introduction
                        </p>
                        <a href="" class="view-btn">
                            <img src="https://www.flyertrade.com/assets/images/icons/edit.svg" alt="View"
                                class="eye-icon" />
                            Edit
                        </a>
                    </div>
                    <div class="setting-wrapper">
                        <p class="description">
                            Lorem ipsum et ipsum habitasse nulla nisi aenean non ut porta vulputate viverra tellus amet
                            euismod justo in placerat penatibus sit lacinia amet lobortis pretium fusce ultrices tempus
                            vestibulum
                            aliquet faucibus facilisis tempor ac viverra aliquam dignissim mauris non libero neque quam
                            semper magna eget a nisi in in id a cras vitae nunc in ac sodales pulvinar massa enim arcu
                            turpis non
                            iaculis mauris commodo id ante at enim nunc pretium risus in odio semper egestas neque pulvinar
                            vivamus integer libero tincidunt magna ultrices vitae pellentesque malesuada curabitur hendrerit
                            lacus ullamcorper in neque augue sed laoreet morbi integer consequat duis vitae augue elit ut et
                            tristique donec vel varius neque nunc eu enim arcu imperdiet nibh faucibus nec morbi nunc mauris
                            ultrices dis tempor faucibus porttitor feugiat tristique nibh convallis fermentum sed
                            consectetur eget morbi eleifend at vestibulum in proin tempus libero ridiculus at aliquet mauris
                            sed ipsum
                            imperdiet purus faucibus nibh nunc eros ac egestas purus ullamcorper semper magna elementum
                            lacus massa urna faucibus lobortis aliquet nec massa tortor enim sed diam libero
                        </p>
                    </div>

                    <br />
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                        <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                            Information we collect
                        </p>
                        <a href="" class="view-btn">
                            <img src="https://www.flyertrade.com/assets/images/icons/edit.svg" alt="View"
                                class="eye-icon" />
                            Edit
                        </a>
                    </div>
                    <div class="setting-wrapper">
                        <p class="description">
                            Lorem ipsum et ipsum habitasse nulla nisi aenean non ut porta vulputate viverra tellus amet
                            euismod justo in placerat penatibus sit lacinia amet lobortis pretium fusce ultrices tempus
                            vestibulum
                            aliquet faucibus facilisis tempor ac viverra aliquam dignissim mauris non libero neque quam
                            semper magna eget a nisi in in id a cras vitae nunc in ac sodales pulvinar massa enim arcu
                            turpis non
                            iaculis mauris commodo id ante at enim nunc pretium risus in odio semper egestas neque pulvinar
                            vivamus integer libero tincidunt magna ultrices vitae pellentesque malesuada curabitur hendrerit
                            lacus ullamcorper in neque augue sed laoreet morbi integer consequat duis vitae augue elit ut et
                            tristique donec vel varius neque nunc eu enim arcu imperdiet nibh faucibus nec morbi nunc mauris
                            ultrices dis tempor faucibus porttitor feugiat tristique nibh convallis fermentum sed
                            consectetur eget morbi eleifend at vestibulum in proin tempus libero ridiculus at aliquet mauris
                            sed ipsum
                            imperdiet purus faucibus nibh nunc eros ac egestas purus ullamcorper semper magna elementum
                            lacus massa urna faucibus lobortis aliquet nec massa tortor enim sed diam libero
                        </p>
                    </div>
                    <br />
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                        <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                            How we use your information
                        </p>
                        <a href="" class="view-btn">
                            <img src="https://www.flyertrade.com/assets/images/icons/edit.svg" alt="View"
                                class="eye-icon" />
                            Edit
                        </a>
                    </div>
                    <div class="setting-wrapper">
                        <p class="description">
                            Lorem ipsum et ipsum habitasse nulla nisi aenean non ut porta vulputate viverra tellus amet
                            euismod justo in placerat penatibus sit lacinia amet lobortis pretium fusce ultrices tempus
                            vestibulum
                            aliquet faucibus facilisis tempor ac viverra aliquam dignissim mauris non libero neque quam
                            semper magna eget a nisi in in id a cras vitae nunc in ac sodales pulvinar massa enim arcu
                            turpis non
                            iaculis mauris commodo id ante at enim nunc pretium risus in odio semper egestas neque pulvinar
                            vivamus integer libero tincidunt magna ultrices vitae pellentesque malesuada curabitur hendrerit
                            lacus ullamcorper in neque augue sed laoreet morbi integer consequat duis vitae augue elit ut et
                            tristique donec vel varius neque nunc eu enim arcu imperdiet nibh faucibus nec morbi nunc mauris
                            ultrices dis tempor faucibus porttitor feugiat tristique nibh convallis fermentum sed
                            consectetur eget morbi eleifend at vestibulum in proin tempus libero ridiculus at aliquet mauris
                            sed ipsum
                            imperdiet purus faucibus nibh nunc eros ac egestas purus ullamcorper semper magna elementum
                            lacus massa urna faucibus lobortis aliquet nec massa tortor enim sed diam libero
                        </p>
                    </div>

                    <br />
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6vw 0;">
                        <p style="margin: 0; font-size: 0.95vw; color: #333; flex: 1;">
                            Disclosure of your information
                        </p>
                        <a href="" class="view-btn">
                            <img src="https://www.flyertrade.com/assets/images/icons/edit.svg" alt="View"
                                class="eye-icon" />
                            Edit
                        </a>
                    </div>
                    <div class="setting-wrapper">
                        <p class="description">
                            Lorem ipsum et ipsum habitasse nulla nisi aenean non ut porta vulputate viverra tellus amet
                            euismod justo in placerat penatibus sit lacinia amet lobortis pretium fusce ultrices tempus
                            vestibulum
                            aliquet faucibus facilisis tempor ac viverra aliquam dignissim mauris non libero neque quam
                            semper magna eget a nisi in in id a cras vitae nunc in ac sodales pulvinar massa enim arcu
                            turpis non
                            iaculis mauris commodo id ante at enim nunc pretium risus in odio semper egestas neque pulvinar
                            vivamus integer libero tincidunt magna ultrices vitae pellentesque malesuada curabitur hendrerit
                            lacus ullamcorper in neque augue sed laoreet morbi integer consequat duis vitae augue elit ut et
                            tristique donec vel varius neque nunc eu enim arcu imperdiet nibh faucibus nec morbi nunc mauris
                            ultrices dis tempor faucibus porttitor feugiat tristique nibh convallis fermentum sed
                            consectetur eget morbi eleifend at vestibulum in proin tempus libero ridiculus at aliquet mauris
                            sed ipsum
                            imperdiet purus faucibus nibh nunc eros ac egestas purus ullamcorper semper magna elementum
                            lacus massa urna faucibus lobortis aliquet nec massa tortor enim sed diam libero
                        </p>
                    </div>
                </div>
                <br />
                <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="submit-btn">Save Changes</button>
                </div>
                <div id="content" class="tab-content">
                    <h3>Content Moderation</h3>
                    <p>Moderate user content and submissions in this section.</p>
                </div>
            </div>
        </div>
    </div>
<script>
    var countryInput = $("#country_selector");

    countryInput.countrySelect({
        preferredCountries: ['us', 'in', 'gb']
    });

    // make entire field clickable
    const wrapper = document.querySelector(".country-select");

    wrapper.addEventListener("click", function () {
        countryInput.countrySelect("open");
    });
</script>



@endsection
