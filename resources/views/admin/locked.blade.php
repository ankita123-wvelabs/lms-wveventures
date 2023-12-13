
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>Lock Screen</title>

    {{ Html::style('admin/css/style.css') }}
    {{ Html::style('admin/css/style-responsive.css') }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="lock-screen">

    <div class="lock-wrapper">
        <div class="panel lock-box text-center">
            <img alt="lock avatar" src="{{ asset('admin/images/photos/user1.png') }}">
            <div class="locked">
                <i class="fa fa-lock"></i>
            </div>
            <h1>{{ $user['name'] }}</h1>
            <div class="row">
                <form action="{{ route('admin.login') }}" class="form-inline" role="form" method="POST">
                    @csrf
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <input id="email" type="hidden" class="form-control" name="email" placeholder="E-mail Address" value="{{ $user['email'] }}" required autofocus>
                        <input type="password" class="form-control lock-input" placeholder="Password" name="password" required>
                        <button type="submit" class="btn btn-lock pull-right">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Placed js at the end of the document so the pages load faster -->

<!-- Placed js at the end of the document so the pages load faster -->
{{ Html::script('admin/js/jquery-1.10.2.min.js') }}
{{ Html::script('admin/js/bootstrap.min.js') }}
{{ Html::script('admin/js/modernizr.min.js') }}

</body>
</html>
