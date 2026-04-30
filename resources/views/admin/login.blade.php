
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login </title>
    @include('components.adminlte')
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <h>{{ env('APP_NAME') }}</h>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Administrative Login</p>

    <form method="post" action="/login">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Email" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
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
    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src={{ asset('bower_components/jquery/dist/jquery.min.js') }}></script>
<!-- Bootstrap 3.3.7 -->
<script src={{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}></script>
<!-- iCheck -->
<script src={{ asset('plugins/iCheck/icheck.min.js') }}></script>
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
