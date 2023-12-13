<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <link rel="shortcut icon" href="#" type="image/png">

        <title>Login</title>

        {{ Html::style('admin/css/style.css')}}
        {{ Html::style('admin/css/style-responsive.css')}}
    </head>
<body class="login-body">
    <div class="container">
       {{ Form::open(['route' => ['admin.login'], 'class'=>'form-signin', 'method' => 'POST']) }}
    <div class="form-signin-heading text-center">
        <h1 class="sign-title">Sign In</h1>
        <img src="{{ asset('admin/images/login-logo.png') }}" alt=""/>
    </div>
    <div class="login-wrap">
        {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'User ID', 'autofocus', 'required']) }}
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button class="btn btn-lg btn-login btn-block" type="submit">
                    <i class="fa fa-check"></i>
                </button>

               {{--  <div class="registration">
                    Not a member yet?
                    <a class="" href="registration.html">
                        Signup
                    </a>
                </div> --}}
                <label class="checkbox">
                    <input type="checkbox" value="remember-me"> Remember me
                    <span class="pull-right">
                        <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                    </span>
                </label>

            </div>
        {{ Form::close() }}
    </div>

    {{ Html::script('admin/js/jquery-1.10.2.min.js') }}
    {{ Html::script('admin/js/bootstrap.min.js') }}
    {{ Html::script('admin/js/modernizr.min.js') }}
</body>
</html>

