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
    <div class="tab roles-tab" data-target="roles">Roles</div>
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
            });
        });
    });

    // Livewire event listeners
    document.addEventListener('livewire:init', () => {
        // Refresh role table
        Livewire.on('roleSaved', () => {
            Livewire.dispatch('refreshRolesTable');
            Livewire.dispatch('$refresh');
        });

        // Refresh user table
        Livewire.on('userSaved', () => {
            Livewire.dispatch('refreshUsersTable');
            Livewire.dispatch('$refresh');
        });

        // Simple toastr
        Livewire.on('showToastr', (type, message, title) => {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <strong>${title}</strong>
                    <span>${message}</span>
                </div>
            `;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
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
