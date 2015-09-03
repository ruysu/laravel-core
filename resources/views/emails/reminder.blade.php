<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>{{ trans('core::auth.emails.remind.title') }}</h2>
    <div>
      {{ trans('core::auth.emails.remind.body') }} {{ url('user/reset', [$token]) }}.<br/>
      {{ trans('core::auth.emails.remind.body-expire', ['minutes' => config('auth.password.expire', 60)]) }}.
    </div>
  </body>
</html>
