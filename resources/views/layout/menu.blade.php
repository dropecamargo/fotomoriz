<ul class="sidebar-menu">
    <li class="header">Menú de navegación</li>
    <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['reporteedades', 'reporteposfechados', 'reporterecibos', 'reporteresumencobro']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-suitcase"></i> <span>Cartera</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Reportes cartera --}}
            <li class="{{ in_array(Request::segment(1), ['reporteedades', 'reporteposfechados', 'reporterecibos', 'reporteresumencobro']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> Reportes <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reporteedades']) ? 'active' : '' }}">
                        <a href="{{ route('reporteedades.index') }}">
                            <i class="fa fa-circle-o"></i><span>Edades de cartera</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reporteposfechados']) ? 'active' : '' }}">
                        <a href="{{ route('reporteposfechados.index') }}">
                            <i class="fa fa-circle-o"></i><span>Cheques posfechados</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reporterecibos']) ? 'active' : '' }}">
                        <a href="{{ route('reporterecibos.index') }}">
                            <i class="fa fa-circle-o"></i><span>Recibos de caja</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reporteresumencobro']) ? 'active' : '' }}">
                        <a href="{{ route('reporteresumencobro.index') }}">
                            <i class="fa fa-circle-o"></i><span>Resumen de cobro</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['reportearp']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-book"></i> <span>Contabilidad</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Reportes inventario --}}
            <li class="{{ in_array(Request::segment(1), ['reportearp']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> Reportes <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reportearp']) ? 'active' : '' }}">
                        <a href="{{ route('reportearp.index') }}">
                            <i class="fa fa-circle-o"></i><span>Gastos ARP</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['reporteentradassalidas', 'reporteanalisisinventario']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-list"></i> <span>Inventario</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Reportes inventario --}}
            <li class="{{ in_array(Request::segment(1), ['reporteentradassalidas', 'reporteanalisisinventario']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> Reportes <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reporteanalisisinventario']) ? 'active' : '' }}">
                        <a href="{{ route('reporteanalisisinventario.index') }}">
                            <i class="fa fa-circle-o"></i><span>Análisis inventario</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reporteentradassalidas']) ? 'active' : '' }}">
                        <a href="{{ route('reporteentradassalidas.index') }}">
                            <i class="fa fa-circle-o"></i><span>Entradas y salidas</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
