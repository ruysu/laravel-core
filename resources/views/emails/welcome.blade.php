<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>{{ trans('core::auth.emails.welcome.title') }}</h2>
    <div>
      {{ trans('core::auth.emails.welcome.body') }} {{ url('user/login') }}.
    </div>
  </body>
</html>
