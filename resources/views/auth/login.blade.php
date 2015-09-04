@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.login') }}}</h1>
      @include('core::partials.alert')
      {!! bootform()->open()->action(url('user/login'))->post() !!}
        {!! bootform()->text(trans('core::auth.labels.username-email'), 'username')->required() !!}
        {!! bootform()->password(trans('core::auth.labels.password'), 'password')->required() !!}
        {!! bootform()->submit(trans('core::auth.labels.login'), 'btn-primary') !!}
        <p class="text-center">{{{ trans('core::auth.labels.no-account') }}} <a href="{{ route('auth.register') }}">{{{ trans('core::auth.labels.register') }}}</a></p>
        <p class="text-center"><a href="{{ route('auth.remind') }}">{{{ trans('core::auth.labels.forgot-password') }}}</a></p>
      {!! bootform()->close() !!}

@stop
