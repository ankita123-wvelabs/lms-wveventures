<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
  <meta name="description" content="">
  <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}">

  <title>{{ env('APP_NAME') }}</title>

  <!--icheck-->
  {{ Html::style('admin/js/iCheck/skins/minimal/minimal.css') }}
  {{ Html::style('admin/js/iCheck/skins/square/square.css') }}
  {{ Html::style('admin/js/iCheck/skins/square/red.css') }}
  {{ Html::style('admin/js/iCheck/skins/square/blue.css') }}

  <!--dashboard calendar-->
  {{ Html::style('admin/css/clndr.css') }}


  <!--common-->
  {{ Html::style('admin/js/gritter/css/jquery.gritter.css') }}
  {{ Html::style('admin/js/toster/toster.min.css') }}
  {{ Html::style('admin/css/style.css') }}
  {{ Html::style('admin/css/style-responsive.css') }}
  @yield('css')
</head>