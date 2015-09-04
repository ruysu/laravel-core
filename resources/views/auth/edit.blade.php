@extends('core::auth.layout')

@section('content')

      <h1 class="text-center auth-title">{{{ trans('core::auth.labels.my-account') }}}</h1>
      @include('core::partials.alert')
      {!! bootform()->open()->action(url('user/edit'))->post() !!}
        {!! bootform()->bind($user) !!}
        {{-- {!! bootform()->file(trans('core::auth.labels.picture'), 'picture') !!} --}}
        {!! bootform()->text(trans('core::auth.labels.username'), 'username')->required() !!}
        {!! bootform()->email(trans('core::auth.labels.email'), 'email')->required() !!}
        {!! bootform()->text(trans('core::auth.labels.name'), 'name')->required() !!}
        {!! bootform()->submit(trans('core::auth.labels.submit'), 'btn-primary') !!}
      {!! bootform()->close() !!}

@stop
