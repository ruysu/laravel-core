@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.reset-password') }}}</h1>
      @include('core::partials.alert')
      {!! bootform()->open()->action(url('user/remind'))->post() !!}
        {!! bootform()->email(trans('core::auth.labels.email'), 'email')->required() !!}
        {!! bootform()->submit(trans('core::auth.labels.send'), 'btn-primary') !!}
        <p class="text-center">{{{ trans('core::auth.labels.have-account') }}} <a href="{{ route('auth.login') }}">{{{ trans('core::auth.labels.login') }}}</a></p>
      {!! bootform()->close() !!}

@stop
