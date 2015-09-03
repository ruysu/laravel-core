<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>{{ trans('core::auth.emails.active.title') }}</h2>
    <div>
      {{ trans('core::auth.emails.active.body') }} {{ url('user/activate', [$token]) }}.
    </div>
  </body>
</html>
