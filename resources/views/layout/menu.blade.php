<ul class="sidebar-menu">
    <li class="header">Menú de navegación</li>
    <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
    </li>

    {{-- Comercial --}}
    <li class="{{ in_array(Request::segment(1), ['reporte']) ? 'active' : '' }}">
        <a href="{{ route('reporteentradassalidas.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Entradas Salidas</span>
        </a>
    </li>
	<li class="{{ in_array(Request::segment(1), ['reporteanalisisinventario']) ? 'active' : '' }}">
        <a href="{{ route('reporteanalisisinventario.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Analisis Inventario</span>
        </a>
    </li>
	<li class="{{ in_array(Request::segment(1), ['reportearp']) ? 'active' : '' }}">
        <a href="{{ route('reportearp.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Gastos ARP</span>
        </a>
    </li>
	<li class="{{ in_array(Request::segment(1), ['reporteresumencobro']) ? 'active' : '' }}">
        <a href="{{ route('reporteresumencobro.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Resumen Cobro</span>
        </a>
    </li>
	<li class="{{ in_array(Request::segment(1), ['reporteedades']) ? 'active' : '' }}">
        <a href="{{ route('reporteedades.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Edades Cartera</span>
        </a>
    </li>
	<li class="{{ in_array(Request::segment(1), ['reporteposfechados']) ? 'active' : '' }}">
        <a href="{{ route('reporteposfechados.index') }}">
            <i class="fa fa-bar-chart-o"></i><span>Reporte Cheques Posfechados</span>
        </a>
    </li>
	
</ul>