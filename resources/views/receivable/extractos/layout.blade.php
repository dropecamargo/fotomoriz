<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		{{-- Format title --}}
		<title>{{ $type == 'xls' ? substr($title, 0 , 31) : $title }}</title>

		{{-- Include css pdf --}}
		@if($type == 'pdf')
			<style type="text/css">
				body {
					font-size: 8;
					font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
					font-weight: normal;
				}

				@page{
					margin-top: 35px;
					margin-right: 30px;
					margin-left: 30px;
					margin-bottom: 35px;
				}

				.tbtitle {
					width: 100%;
				}

				.subtbtitle {
					width: 100%;
				}

				.company{
					font-size: 12;
					font-weight: bold;
					text-align: center;
				}

				.nit{
					font-size: 10;
					font-weight: bold;
					text-align: center;
					border-bottom: 1px solid black;
				}

				.cliente{
					margin-top: -10px;
					font-size: 8;
					font-weight: bold;
					text-align: center;
				}

				.nit_cliente{
					font-size: 7;
					font-weight: bold;
					text-align: center;
				}

				.title{
					font-size: 9;
					font-weight: bold;
					text-align: center;
				}

				.titleespecial{
					font-size: 10;
					background-color: #000000;
					color: #FFFFFF;
				}

				.rtable {
					width: 100%;
				    border-collapse: collapse;
				}

				.rtable th {
					border: 1px solid black;
					padding-left: 2px;
					font-size: 11px;
				}

				.rtable td, th {
					height: 19px;
				}

				.rtablemid {
					width: 100%;
				    border-collapse: collapse;
					border: 1px solid black;
				}

				.rtablemid th {
					border: 0px solid black;
					padding-left: 2px;
					font-size: 22px;
				}

				.rtablemid td {
					font-size: 22px;
				}

				.htable {
					width: 100%;
				}

				.htable td, th {
					text-align: left;
				}

				.foottable td, th {
					text-align: left;
					font-size: 9px;
				}

				.left {
					text-align: left;
				}

				.right {
					text-align: right;
				}

				.center{
					text-align: center;
				}

				.bold{
					font-weight: bold;
				}

				.width-100 {
					width: 100%;
				}

				.page-break {
				    page-break-after: always;
				}
			</style>
		@endif
	</head>
	<body>
		{{-- Title --}}
		{{--*/ $empresa = App\Models\Base\Empresa::getEmpresa(); /*--}}
		@include('reports.title')
		<br/>

		@yield('content')
	</body>
</html>
