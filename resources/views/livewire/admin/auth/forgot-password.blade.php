<div>
    <h2>Reset Your Password <br>
        <span class="text-reset">Enter your email for password reset link</span>
    </h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="sendResetLink" id="resetForm" novalidate>
        <div class="mb-3 form-group">
            <label for="email" class="form-label">Email</label>
            <input wire:model.defer="email" type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" placeholder="Enter your email" required wire:loading.attr="disabled">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>

        <button type="submit" class="btn btn-submit" wire:loading.attr="disabled" wire:target="sendResetLink">
            <span wire:loading.remove wire:target="sendResetLink">
                Reset Password
            </span>
            <span wire:loading wire:target="sendResetLink">
                <i class="fa-solid fa-spinner fa-spin"></i> Sending...
            </span>
        </button>
    </form>
</div>
