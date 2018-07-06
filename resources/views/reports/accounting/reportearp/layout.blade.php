<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Format title --}}
		<title>{{ $type == 'xls' ? substr($title, 0 , 31) : $title }}</title>

		<style type="text/css">
			body {
				font-size: 8;
				font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
				font-weight: normal;
			}

			.noborder {
			    border: none !important;
			}

			.color-cell {
				 background-color: #FFFF99;
			}

			.bold-cell {
				font-size: 10;
				font-weight: bold;
			}

			tr > td {
			    border: 1px solid #000000;
			}
		</style>
	</head>
	<body>
		@yield('content')
	</body>
</html>
