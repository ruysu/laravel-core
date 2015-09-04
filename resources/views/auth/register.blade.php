@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.register') }}}</h1>
      @include('core::partials.alert')
      {!! bootform()->open()->action(url('user/register'))->post() !!}
        {!! bootform()->text(trans('core::auth.labels.name'), 'name')->required() !!}
        {!! bootform()->text(trans('core::auth.labels.username'), 'username')->required() !!}
        {!! bootform()->email(trans('core::auth.labels.email'), 'email')->required() !!}
        {!! bootform()->password(trans('core::auth.labels.password'), 'password')->required() !!}
        {!! bootform()->password(trans('core::auth.labels.password_confirmation'), 'password_confirmation')->required() !!}
        {!! bootform()->submit(trans('core::auth.labels.register'), 'btn-primary') !!}
        <p class="text-center">{{{ trans('core::auth.labels.have-account') }}} <a href="{{ route('auth.login') }}">{{{ trans('core::auth.labels.login') }}}</a></p>
      {!! bootform()->close() !!}

@stop
