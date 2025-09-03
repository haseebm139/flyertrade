<div>
    <h2>Reset Your Password <br>
        <span class="text-reset">Enter your new password</span>
    </h2>


    <form
        wire:submit.prevent="resetPassword"
        id="resetForm"
        novalidate
    >
        <!-- New Password -->
        <div class="mb-3 password-wrapper">
            <label
                for="newPassword"
                class="form-label"
            >New password</label>
            <input
                wire:model="password"
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="newPassword"
                placeholder="Enter password"
                required
            >
            <i
                class="fa-solid fa-eye toggle-password"
                data-target="newPassword"
            ></i>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
        <br>
        <!-- Confirm Password -->
        <div class="mb-3 password-wrapper">
            <label
                for="confirmPassword"
                class="form-label"
            >Confirm password</label>
            <input
                wire:model="password_confirmation"
                type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="confirmPassword"
                placeholder="Enter password"
                required
            >
            <i
                class="fa-solid fa-eye toggle-password"
                data-target="confirmPassword"
            ></i>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button
            type="submit"
            class="btn btn-submit"
        >Reset</button>
    </form>


</div>
