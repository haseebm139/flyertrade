<div>
    <h4>Welcome <img
            src="{{ asset('assets/images/icons/hand-shake.png') }}"
            alt=""
        ></h4>
    <h2>Flyertrade Admin</h2>

    <form
        wire:submit.prevent="login"
        id="loginForm"
        novalidate
    >
        <div class="mb-3">
            <label
                for="email"
                class="form-label"
            >Email</label>

            <input
                wire:model="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                placeholder="Enter email"
                required
            >
            @error('email')
                <span class="text-danger ">{{ $message }}</span>
            @enderror
        </div>
        <br>
        <div class="mb-1 form-group">
            <label
                for="password"
                class="form-label"
            >Password</label>
            <input
                wire:model="password"
                type="password"
                class="form-control @error('email') is-invalid @enderror"
                id="password"
                placeholder="Enter password"
                required
            >
            <i
                class="fa-solid fa-eye toggle-password"
                id="togglePassword"
            ></i>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>

        <div class="forgot">
            <a
                href="{{ route('password.request') }}"
                class="forgot"
            >
                Forgot Password</a>
        </div>

        <button
            type="submit"
            class="btn btn-submit"
        >Sign in</button>
    </form>
    <script src="{{ asset('assets/js/foam-validations.js') }}"></script>



</div>
