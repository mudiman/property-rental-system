<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Smoor</title>

        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="/css/font-awesome.min.css">

        <!-- Ionicons -->
        <link rel="stylesheet" href="/css/ionicons.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="/css/AdminLTE.min.css">

        <!-- iCheck -->
        <link rel="stylesheet" href="/css/skins/_all-skins.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ asset("/img/Smoor_Logo_Red.png") }}" srcset="{{ asset("/img/Smoor_Logo_Red.png") }} 1x, {{ asset("/img/Smoor_Logo_Red@2x.png") }} 2x, {{ asset("/img/Smoor_Logo_Red@3x.png") }} 3x">
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body panel panel-default">
                <div class="panel-body" style="    background-color: #D8D8DD;margin: -20px;padding: 11px;font-size: 19px;font-weight: bold;margin-bottom: 10px;padding-left: 18px;">Reset your password</div>
                <p class="">Tips, User at least 8 characters. Don't re-use passwords from other websites or include obvious words like your name or email</p>
                @if(isset($errors) && $errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                {!! Form::open(['route' => ['api.user.update.password', $user_id, $forgot_password_verification_code]]) !!}
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group ">
                        <!-- /.col -->
                        <div class="">
                            <button type="submit" style="color:#ffffff;background-color:#f64662;border-radius:4px;border-top:0px solid transparent;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;text-align:center" class="btn btn-primary btn-block btn-flat">Submit Password</button>
                        </div>
                        <br/>
                        <!-- /.col -->
                        <div class="">
                            <p>By clicking 'Save & Continue', you confirm that you accept the Terms of Service & Privacy Policy</p>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.login-box-body -->
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

        <!-- AdminLTE App -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/js/app.min.js"></script>

        <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
        </script>
    </body>
</html>