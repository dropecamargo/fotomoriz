<!DOCTYPE html>
    <!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
    -->
    <html>
    <head>
        <meta charset="UTF-8">
        <title>{{ config('koi.app.name') }} :: Login</title>
        <link rel="icon" type="image/png" href="{{ asset(config('koi.app.image.logo')) }}" />

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        {{-- Secure tags koi --}}
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        {{-- Vendor css --}}
        <link href="{{ asset("/css/vendor.min.css") }}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="{{ asset(config('koi.app.image.logo')) }}" alt="{{ config('koi.app.sitename') }}"/><br>
			</div>

			@if (count($errors) > 0)
			<div class="callout callout-warning">
				@foreach ($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
			</div>
			@endif

			<div class="login-box-body">
				<p class="login-box-msg">Ingresa con tu usuario y contraseña</p>

				{!! Form::open(['route' => 'login', 'id' => 'form-login-account', 'data-toggle' => 'validator']) !!}
					<div class="form-group has-feedback">
						<input type="text" name="username" class="form-control" placeholder="Usuario" value="{{ old('username') }}" pattern="^[_A-z0-9]{1,}$" maxlength="15"required>
						<span class="glyphicon glyphicon-user form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="password" class="form-control" placeholder="Contraseña" required>
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-offset-7 col-xs-5">
							<button type="submit" class="btn btn-block btn-danger" style="vertical-align: middle">{{ trans('app.login') }}</button>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>

	    {{-- Vendor KOI App --}}
	    <script src="{{ asset ("/js/vendor.min.js") }}" type="text/javascript"></script>
	    {{-- KOI App --}}
	    <script src="{{ asset ("/js/app.min.js") }}" type="text/javascript"></script>
	</body>
</html>