@extends('admin.layouts.app')

@section('title', 'Roles and Permissions')
@section('header', 'Roles and Permissions')

@section('content')
<style>
    #addUserModal .custom-select-input ,#addUserModal .form-select{
        background: #F6F6F6;
        border-color:#ddd;
        color:#333 !important;
    }
    #addUserModal .custom-select-text{
        color:#777777 !important;
            font-size: 0.9vw;
    }
</style>
<div class="container">
    <h1 class="page-title">Roles and Permissions</h1>
</div>

<!-- Top Tabs -->
<div class="tabs-section mb-3">
    <div class="tab active roles-tab" data-target="users">Users</div>
    <div class="tab roles-tab" id="ROlesss_tabb" data-target="roles">Roles</div>
</div>

<!-- Users Tab Content -->
<div id="users" class="main-tab-content active">
    <livewire:admin.users.users-table />
</div>

<!-- Roles Tab Content -->
<div id="roles" class="main-tab-content">
    <livewire:admin.roles.roles-table />
</div>

<!-- Role Form Modal -->
<livewire:admin.roles.role-form :key="'role-form-' . time()" />

<!-- User Form Modal -->
<livewire:admin.users.user-form :key="'user-form-' . time()" />


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select only top-level tabs
        const tabs = document.querySelectorAll('.roles-tab');
        const tabContents = document.querySelectorAll('.main-tab-content'); // 🔹 Changed class selector

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');

                // Remove active class from all
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add to clicked
                this.classList.add('active');
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }

                // Clear URL parameters and hash
                if (window.location.search || window.location.hash) {
                    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.pushState({path: cleanUrl}, '', cleanUrl);
                }
            });
        });
    });

    // Livewire event listeners
    document.addEventListener('livewire:init', () => {
        // Refresh role table
        Livewire.on('roleSaved', () => {
            Livewire.dispatch('refreshRolesTable');
        });

        // Refresh user table
        Livewire.on('userSaved', () => {
            Livewire.dispatch('refreshUsersTable');
        });
    });

    // Toast animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* 🔹 Fix nested tab visibility issue */
        .main-tab-content {
            display: none;
        }
        .main-tab-content.active {
            display: block;
        }
    `;
    document.head.appendChild(style);
</script>

@endsection
