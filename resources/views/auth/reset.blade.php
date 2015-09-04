@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.reset-password') }}}</h1>
      @include('core::partials.alert')
      {!! bootform()->open()->action(url('user/reset'))->post() !!}
        {!! bootform()->hidden('token')->value($token) !!}
        {!! bootform()->email(trans('core::auth.labels.email'), 'email')->required() !!}
        {!! bootform()->password(trans('core::auth.labels.password'), 'password')->required() !!}
        {!! bootform()->password(trans('core::auth.labels.password_confirmation'), 'password_confirmation')->required() !!}
        {!! bootform()->submit(trans('core::auth.labels.submit'), 'btn-primary') !!}
      {!! bootform()->close() !!}

@stop
