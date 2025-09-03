<div>
    <h2>Reset Your Password <br>
        <span class="text-reset">Enter your email for password reset link</span>
    </h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form
        wire:submit.prevent="sendResetLink"
        id="resetForm"
        novalidate
    >
        <div class="mb-3 form-group">
            <label
                for="email"
                class="form-label"
            >Email</label>
            <input
                wire:model="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                placeholder="Enter your email"
                required
            >
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>

        <button
            type="submit"
            class="btn btn-submit"
        >Reset Password</button>
    </form>
</div>
