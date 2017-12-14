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

				.title{
					font-size: 10;
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
				}

				.rtable td, th {
					height: 19px;
				}

				.rtable tr:nth-child(even) {
					background-color: #f2f2f2
				}

				.htable {
					width: 100%;
				}

				.htable td, th {
					text-align: left;
				}

				.brtable {
					width: 100%;
				    border-collapse: collapse;
				}

				.brtable th {
					border: 1px solid black;
					padding-left: 2px;
				}

				.brtable td {
					border: 1px solid black;
					padding-left: 2px;
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

				.size-6 {
					font-size: 6;
				}

				.size-7 {
					font-size: 7;
				}

				.border-left {
					border-left: 1px solid black;
					padding-left: 2px;
				}

				.border-right {
					border-right: 1px solid black;
					padding-left: 2px;
				}

				.border-top {
					border-top: 1px solid black;
					padding-top: 2px;
				}

				.height-40 {
					height: 40px;
				}

				.height-19 {
					height: 19px;
				}

				.margin-top-60 {
					margin-top: 60px;
				}

				.margin-bottom-60 {
					margin-bottom: 60px;
				}

				.line-red{
					background-color: #E55536;
					color: #FFFFFF;
				}
			</style>
		@endif
	</head>
	<body>
		<script type="text/php">
		    if (isset($pdf)) {
				// Configurar (positionX, positionY, textp, font-family, font-size, font-color, word_space, char_space, angle)
				$pdf->page_text(279, $pdf->get_height() - 15, utf8_decode("Pagina {PAGE_NUM} de {PAGE_COUNT}"), 'DejaVu Sans', 7, array(0,0,0), 0.0, 0.0, 0.0);
		    }
		</script>

		{{-- Title --}}
		{{--*/ $empresa = App\Models\Base\Empresa::getEmpresa(); /*--}}
		@include('reports.title')
		<br/>

		@yield('content')
	</body>
</html>
