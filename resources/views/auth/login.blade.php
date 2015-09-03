@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.login') }}}</h1>
      {{-- @include('partials.alert') --}}
      {{ $form->open()->action('user/login')->post() }}
        {{ $form->text(trans('core::auth.labels.username-email'), 'email')->required() }}
        {{ $form->password(trans('core::auth.labels.password'), 'password')->required() }}
        {{ $form->submit(trans('core::auth.labels.login'), 'btn-block btn-primary') }}
        <p class="color-gray-light gutter-top no-margin-bottom text-center">{{{ trans('core::auth.labels.no-account') }}} <a href="{{ url('user/register') }}">{{{ trans('core::auth.labels.register') }}}</a></p>
        <p class="color-gray-light gutter-bottom text-center"><a href="{{ url('user/remind') }}">{{{ trans('core::auth.labels.forgot-password') }}}</a></p>
        @include('partials.social')
      {{ $form->close() }}

@stop
