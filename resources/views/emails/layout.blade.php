<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		{{--*/
			$bgcolor_inst = #00a65a;
			$color_white = '#ffffff';
			$font_weigth = 'normal';
			$font = "'Lucida Sans Unicode', 'Lucida Grande', sans-serif";
			$font_family = "font-family:$font;";
			$font_size_text = "font-size:13.5px;";
			$font_size_text_2 = "font-size:18px;";
			$font_weight_light = "font-weight:lighter;";
			$font_color_white = "color:$color_white;";
			$color_inst_alt = $bgcolor_inst; // #ff5656
			$bgcolor_inst_alt = "background-color:$color_inst_alt;";
			$font_color_inst_alt = "color:$color_inst_alt;";
			$color_inst_alt_dark = $bgcolor_inst; // #f23d3d
			$gray_1 = "#f7f7f7";
			$gray_2 = "#ededed";
			$gray_3 = "#727272";
			$gray_4 = "#3a3a3a";
			$text_color_1 = "color:$gray_4;";
			$text_color_2 = "color:$gray_3";
			$bg_gray_1 = "background-color:$gray_1;";
			$header_border_top = "border-top:7px solid $bgcolor_inst;";
			$borders = "border-left:1px solid $gray_2;border-right:1px solid $gray_2;";
			$table_wrap_width = '100%';
			$table_wrap_height = '100%';
			$table_inner_width = 700;
			$table_inner_align = 'center';
			$table_inner_width_content = 520;
			$_inst_url = config('koi.app.site');
			$_inst_name = config('koi.app.name');
			$_inst_img = asset(config('koi.app.image.logo'));
		/*--}}

		<table border="0" cellspacing="0" cellpadding="0" height="{{ $table_wrap_height }}" width="{{ $table_wrap_width }}">
	        <tr>
	        	<td align="{{ $table_inner_align }}" valign="top">

	                {{-- header --}}
	                <table border="0" cellpadding="10" cellspacing="0" width="{{ $table_inner_width }}"; id="templatePreheader">
	                	{{-- header logo --}}
	                    <tr>
	                        <td align="{{ $table_inner_align }}" valign="top" class="header-logo" style="{{ $header_border_top }}{{ $borders }}">
	                            <table border="0" cellspacing="0" cellpadding="0" width="{{ $table_inner_width_content }}";>
	                            	<tr>
	                            		<td valign="top">
	                                    	<div>
	                                    		<div style="text-align:center; padding: 10px 0;">
	                                    			<span>
        												<a href="{{{ $_inst_url }}}" title="{{ config('koi.app.name') }}">
	                                    					<img align="{{ $table_inner_align }}" id="logo-reb-header" src="{{ $_inst_img }}" style="max-width: 600px; height: auto;">
        												</a>
	                                    			</span>
	                                    		</div>
											</div>
	                                	</td>
	                                </tr>
	                            </table>
	                        </td>
	                    </tr>
	                </table>
	                {{-- header end --}}

	                {{-- content --}}
	            	<table border="0" cellspacing="0" cellpadding="0" width="{{ $table_inner_width }}";>
	                	<tr>
	                    	<td align="{{ $table_inner_align }}" valign="top" style="{{ $bg_gray_1 }} {{ $borders }}">
	                        	<table border="0" cellspacing="0" cellpadding="0" width="620" style="{{ $font_family }} {{ $font_size_text }} {{ $text_color_2 }}">
	                                <tr>
	                                	<td>
	                                		@yield('content')
	                                	</td>
	                                </tr>
	                            </table>
	                        </td>
	                    </tr>
	            	</table>
	            </td>
	        </tr>
		</table>
	</body>
</html>
