<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
</div>
<script>
    document.addEventListener('livewire:init', () => {
        // 🔹 Success
        Livewire.on('swal-success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data.title ?? 'Success',
                text: data.message ?? '',
                timer: data.timer ?? (data.showConfirmButton ? undefined : 1500),
                showConfirmButton: data.showConfirmButton ?? false,
            }).then(() => {
                if (data.redirect) window.location.href = data.redirect;
            });
        });

        // 🔹 Error
        Livewire.on('swal-error', (data) => {
            Swal.fire({
                icon: 'error',
                title: data.title ?? 'Oops...',
                text: data.message ?? 'Something went wrong!',
                confirmButtonColor: '#d33',
            });
        });

        // 🔹 Confirmation
        Livewire.on('swal-confirm', (data) => {
            Swal.fire({
                icon: data.icon ?? 'warning',
                title: data.title ?? 'Are you sure?',
                text: data.message ?? 'This action cannot be undone!',
                showCancelButton: true,
                confirmButtonText: data.confirmButtonText ?? 'Yes',
                cancelButtonText: data.cancelButtonText ?? 'Cancel',
                confirmButtonColor: data.confirmButtonColor ?? '#3085d6',
                cancelButtonColor: data.cancelButtonColor ?? '#d33',
            }).then((result) => {
                if (result.isConfirmed && data.callback) {
                    Livewire.dispatch(data.callback, data.payload ?? {});
                }
            });
        });
    });
</script>
