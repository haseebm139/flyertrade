@extends('admin.layouts.app')

@section('title', 'Roles and Permissions')
@section('header', 'Roles and Permissions')
@section('content')

    <div class="container">
        <h1 class="page-title">Roles and Permissions</h1>
    </div>

    <!-- Top Stat Cards -->
    <div class="tabs-section">
        <div class="tab active roles-tab" data-target="users">Users</div>
        <div class="tab roles-tab" data-target="roles">Roles</div>
    </div>

    <!-- Users Tab Content -->
    <div id="users" class="tab-content active">
        <livewire:admin.users.users-table />
    </div>

    <!-- Roles Tab Content -->
    <div id="roles" class="tab-content">
        <livewire:admin.roles.roles-table />
    </div>

    <!-- Role Form Modal -->
    <livewire:admin.roles.role-form :key="'role-form-' . time()" />

    <!-- User Form Modal -->
    <livewire:admin.users.user-form :key="'user-form-' . time()" />

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.roles-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');

                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
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
            // Listen for role modal events
            Livewire.on('openRoleModal', (roleId = null, mode = 'create') => {
                console.log('openRoleModal event received:', roleId, mode);
                // Find the RoleForm component specifically
                const roleFormElement = document.querySelector('[wire\\:id*="admin.roles.role-form"]');
                console.log('RoleForm element found:', roleFormElement);
                if (roleFormElement) {
                    const roleFormId = roleFormElement.getAttribute('wire:id');
                    console.log('RoleForm ID:', roleFormId);
                    const roleForm = Livewire.find(roleFormId);
                    console.log('RoleForm component:', roleForm);
                    if (roleForm) {
                        roleForm.call('openModal', roleId, mode);
                    }
                }
            });

            // Listen for user modal events
            Livewire.on('openUserModal', (userId = null, mode = 'create') => {
                // Find the UserForm component specifically
                const userFormElement = document.querySelector('[wire\\:id*="admin.users.user-form"]');
                if (userFormElement) {
                    const userFormId = userFormElement.getAttribute('wire:id');
                    const userForm = Livewire.find(userFormId);
                    if (userForm) {
                        userForm.call('mount', userId, mode === 'edit');
                        userForm.set('showModal', true);
                    }
                }
            });

            // Listen for role saved events
            Livewire.on('roleSaved', () => {
                // Refresh the roles table
                Livewire.dispatch('$refresh');
            });

            // Listen for user saved events
            Livewire.on('userSaved', () => {
                // Refresh the users table
                Livewire.dispatch('$refresh');
            });

            // Listen for toastr events
            Livewire.on('showToastr', (type, message, title) => {
                // Simple toast notification
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

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        });

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    </script>

@endsection
