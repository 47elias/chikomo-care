<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @include('components.adminlte')

    <!-- Custom Modernized Overrides for AdminLTE Structure -->
    <style>
        body.login-page {
            background: #f8fafc !important; /* Soft, clean slate background */
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            margin: 0;
            width: 380px;
        }
        .login-logo h {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #4f46e5 !important; /* Premium Indigo Brand Accent */
            text-transform: uppercase;
        }
        .login-box-body {
            background: #ffffff !important;
            padding: 30px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        }
        .login-box-msg {
            font-size: 11px !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b !important;
            padding: 0 0 24px 0 !important;
        }
        .form-group.has-feedback {
            margin-bottom: 20px;
        }
        .form-control {
            height: 42px !important;
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            font-size: 13px !important;
            color: #334155 !important;
            transition: all 0.2s ease-in-out;
        }
        .form-control:focus {
            border-color: #6366f1 !important;
            background: #ffffff !important;
        }
        .form-control-feedback {
            line-height: 42px !important;
            height: 42px !important;
            color: #94a3b8 !important;
        }
        .btn-flat {
            border-radius: 8px !important;
            height: 42px;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            transition: all 0.15s ease;
        }
        .btn-flat:hover, .btn-flat:focus {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
        }
        .alert-danger {
            background-color: #fef2f2 !important;
            border: 1px solid #fee2e2 !important;
            color: #991b1b !important;
            border-radius: 8px !important;
            font-size: 12px;
            text-shadow: none !important;
        }
        .login-box-body a {
            color: #4f46e5 !important;
            font-size: 12px;
            font-weight: 600;
        }
        .login-box-body a:hover {
            color: #4338ca !important;
            text-decoration: underline;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <h>{{ env('APP_NAME') }}</h>
    <div class="logo">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Administrative Login</p>

    <form method="post" action="/login">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="padding-left: 15px; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row" style="display: flex; align-items: center;">
        <div class="col-xs-8">
          <div class="checkbox icheck" style="margin-top: 0; margin-bottom: 0;">
            <label style="font-size: 13px; color: #475569; font-weight: 600; user-select: none;">
              <input type="checkbox" name="remember"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div style="margin-top: 20px; border-t: 1px solid #f1f5f9; padding-top: 15px; display: flex; justify-content: space-between;">
        <a href="#">Forgot password?</a>
        <a href="register.html" class="text-center">Register membership</a>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
