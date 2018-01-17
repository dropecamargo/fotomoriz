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

				.itable {
					width: 100%;
					border-collapse: collapse;
					font-size: 7;
				}

				.itable tr:nth-child(even) {
					background-color: #f2f2f2
				}

				.itable th {
					border: 1px solid black;
					font-size: 6;
				}

				.itable td, th {
					height: 19px;
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

				.size-8 {
					font-size: 8;
				}

				.footer {
					width: 100%;
					position: absolute;
	    			bottom: 40;
				}
			</style>
		@endif
	</head>
	<body>
		<script type="text/php">
		    if (isset($pdf)) {
				// Configurar (positionX, positionY, textp, font-family, font-size, font-color, word_space, char_space, angle)
				$pdf->page_text(279, $pdf->get_height() - 15, utf8_encode("Pagina {PAGE_NUM} de {PAGE_COUNT}"), 'DejaVu Sans', 7, array(0,0,0), 0.0, 0.0, 0.0);
		    }
		</script>

		{{-- Title --}}
		{{--*/ $empresa = App\Models\Base\Empresa::getEmpresa(); /*--}}
		@include('reports.title')

		@yield('content')
	</body>
</html>
