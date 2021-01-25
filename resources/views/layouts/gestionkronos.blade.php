
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Kronos - Admin</title>
    <link href="css/login-register.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">

    <style>
        #myVideo {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index:0;
        }
    </style>
</head>

<body class="skin-default card-no-border">

<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Kronos - Admin</p>
    </div>
</div>

<video autoplay muted loop id="myVideo">
    <source src="videos/login.mp4" type="video/mp4">
</video>

<div style="position:fixed;background-color:rgba(0,0,0,0.8);width:100%;height:2000px;z-index:1;"></div>

<section id="wrapper" style="position:absolute;z-index:2;">
    <div class="login-register">
        <div class="login-box card" style="background-color: rgba(0,0,0,0);">
            <div class="card-body">
                <form class="form-horizontal form-material" id="loginform"method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <img src="images/logo_admin.png" style="width:100%;margin-bottom:50px;">
                    <h3 class="text-center m-b-20" style="color:#fff;">Mon compte</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="email" type="email" class="form-control" style='color: white;'  placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif


                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">

                            <input id="password" type="password" class="form-control" style='color: white;' placeholder="Password"  name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}>
                                    <label class="custom-control-label" style="color:#fff;" for="customCheck1">Se souvenir de moi</label>
                                </div>
                                <div class="ml-auto">
                                    <a class="btn btn-link" href="{{ url('/password/reset') }}"><i class="fas fa-lock m-r-5" style="color:#fff;"></i> Mot de passe oublié?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <input type="submit"class="btn btn-block btn-lg btn-info btn-rounded" value="Me connecter">Me connecter

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<script src="node_modules/jquery-3.2.1.min.js"></script>
<script src="node_modules/popper.min.js"></script>
<script src="node_modules/bootstrap.min.js"></script>

<script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
</script>

</body>
</html>