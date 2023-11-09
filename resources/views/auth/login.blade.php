<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v3.4.0
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<html lang="en">
    <head>
        {{-- meta --}}
        @include('layouts._meta')

        <title>Login | Anwar Motor</title>

        {{-- css --}}
        @include('layouts._css')
    </head>
    <body class="c-app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card col-lg-4 col-md-6 col-sm-8">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <h1 class="text-center mb-3">{{ __('Login') }}</h1>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
                                    <svg class="c-icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                    </svg></span>
                                </div>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend"><span class="input-group-text">
                                    <svg class="c-icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                    </svg></span>
                                </div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary px-4" type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- js --}}
        @include('layouts._js')

    </body>
</html>
