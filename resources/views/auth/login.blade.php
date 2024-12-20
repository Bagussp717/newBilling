@extends('layouts.login')

@section('title', 'Login')

@section('content')
    <div class="overflow-hidden position-relative min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="mx-auto col-lg-10 col-xl-9">
                    <div class="flex-row overflow-hidden border-0 shadow card">
                        <div class="card-img-left d-none d-md-flex">
                            <!-- Background image for card set in CSS! -->
                        </div>
                        <div class="p-4 card-content">
                            <div class="py-1 text-nowrap logo-img d-block w-100">
                                <h1>Semesta Billing</h1>
                            </div>
                            <p class="mb-2">Solusion Billing Connection</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="password" class="form-label">Email</label>
                                    <input type="email"
                                        class="form-control input-custom @error('email') is-invalid @enderror"
                                        id="email" name="email" aria-describedby="emailHelp"
                                        value="{{ old('email') }}" required autofocus autocomplete="username">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4 position-relative">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password"
                                        class="form-control input-custom-password @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="current-password">
                                    <span class="password-toggle" id="password-toggle">
                                        <i class="ti ti-eye-off" id="password-icon"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>

                                <button type="submit" class="py-8 mb-4 btn btn-primary w-100 fs-4 rounded-2">Sign
                                    In</button>
                                <div class="d-flex align-items-center justify-content-center">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
