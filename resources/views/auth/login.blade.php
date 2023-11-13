@extends('layouts.app')

@section('content')
<div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
            <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                <div class="m-stack m-stack--hor m-stack--desktop">
                    <div class="m-stack__item m-stack__item--fluid">
                        <div class="m-login__wrapper">
                            <div class="m-login__logo">
                                <a href="#">
                                    <img src="{{ asset('public/default/images/login_logo.png') }}">
                                </a>
                            </div>
                            <div class="m-login__signin">
                                <div class="m-login__head">
                                    <h3 class="m-login__title">
                                        NESTERRA
                                    </h3>
                                </div>
                                <form class="m-login__form m-form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group m-form__group">
                                        <input id="email" type="email" class="form-control m-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                                        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input id="password" type="password" class="form-control m-input m-login__form-input--last @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row m-login__form-sub">
                                        <div class="col m--align-left">
                                            <label class="m-checkbox m-checkbox--focus">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                Remember me
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="m-login__form-action">
                                        <button id="m_login_signin_submit" type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <img  class="sutlej-logo" src="{{ asset('public/default/images/sutlej-logo.png') }}">
            </div>

            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url(public/default/images/login_background.jpg)">

                <span class="bg-overlay"><div class="project-title">Sample Management System</div></span>
            
                <div class="m-grid__item m-grid__item--middle">
                    <!-- <h3 class="m-login__welcome">
                        Join Our Community
                    </h3>
                    <p class="m-login__msg">
                        Sample Management System
                    </p> -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
    <script type="text/javascript">
        $(document).ready(function() {
            @if ($status = Session::get('success'))
                swal({
                    title: "Success!",
                    text: "{{ $status }}",
                    type: "success"
                });
            @endif
            @if ($status = Session::get('error'))
                swal({
                    title: "Failed!",
                    text: "{{ $status }}",
                    type: "error"
                });
            @endif
            @if ($status = Session::get('access_prohibited'))
                swal({
                    title: "Access prohibited!",
                    text: "{{ $status }}",
                    type: "error"
                });
            @endif
        });
    </script>
@endsection
