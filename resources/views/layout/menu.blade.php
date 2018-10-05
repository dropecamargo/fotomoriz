<ul class="sidebar-menu">
    <li class="header">Menú de navegación</li>
    <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
    </li>

    {{-- Administracion --}}
    <li class="treeview {{ in_array(Request::segment(1), ['roles', 'tercerosinterno', 'permisos', 'modulos']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-cog"></i> <span>Administración</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Modulos administracion --}}
            <li class="{{ in_array(Request::segment(1), ['roles', 'tercerosinterno']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-wpforms"></i> Módulos <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::segment(1) == 'tercerosinterno' ? 'active' : '' }}">
                        <a href="{{ route('tercerosinterno.index') }}"><i class="fa fa-users"></i> Terceros interno</a>
                    </li>
                    <li class="{{ Request::segment(1) == 'roles' ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}"><i class="fa fa-address-card-o"></i> Roles</a>
                    </li>
                </ul>
            </li>

            {{-- Referencias administracion --}}
            <li class="{{ in_array(Request::segment(1), ['permisos', 'modulos']) ? 'active' : '' }}">

                <a href="#">
                    <i class="fa fa-circle-o"></i> Referencias <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::segment(1) == 'permisos' ? 'active' : '' }}">
                        <a href="{{ route('permisos.index') }}"><i class="fa fa-circle-o"></i> Permisos</a>
                    </li>
                    <li class="{{ Request::segment(1) == 'modulos' ? 'active' : '' }}">
                        <a href="{{ route('modulos.index') }}"><i class="fa fa-circle-o"></i> Modulos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['amortizaciones','generarintereses', 'enviarintereses', 'rintereses', 'reporteedades', 'reporteposfechados', 'reporterecibos', 'reporteresumencobro', 'reporteverextractos']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-suitcase"></i> <span>Cartera</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Modulos cartera --}}
            <li class="{{ in_array(Request::segment(1), ['generarintereses', 'enviarintereses', 'amortizaciones']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-wpforms"></i> Módulos <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::segment(1) == 'amortizaciones' ? 'active' : '' }}">
                        <a href="{{ route('amortizaciones.index') }}"><i class="fa fa-apple"></i> Amortizaciones</a>
                    </li>
                    <li class="{{ Request::segment(1) == 'generarintereses' ? 'active' : '' }}">
                        <a href="{{ route('generarintereses.index') }}"><i class="fa fa-pie-chart"></i> Generar intereses</a>
                    </li>
                    <li class="{{ Request::segment(1) == 'enviarintereses' ? 'active' : '' }}">
                        <a href="{{ route('enviarintereses.index') }}"><i class="fa fa-share-square"></i> Enviar intereses</a>
                    </li>
                </ul>
            </li>

            {{-- Reportes cartera --}}
            <li class="{{ in_array(Request::segment(1), ['reporteedades', 'reportefacturaselectronicas', 'rintereses', 'reporteposfechados', 'reporterecibos', 'reporteresumencobro']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> Reportes <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reporteposfechados']) ? 'active' : '' }}">
                        <a href="{{ route('reporteposfechados.index') }}">
                            <i class="fa fa-circle-o"></i><span>Cheques posfechados</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reporteedades']) ? 'active' : '' }}">
                        <a href="{{ route('reporteedades.index') }}">
                            <i class="fa fa-circle-o"></i><span>Edades de cartera</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['reportefacturaselectronicas']) ? 'active' : '' }}">
                        <a href="{{ route('reportefacturaselectronicas.index') }}">
                            <i class="fa fa-circle-o"></i><span>Facturas electronicas</span>
                        </a>
                    </li>
                    <li class="{{ in_array(Request::segment(1), ['rintereses']) ? 'active' : '' }}">
                        <a href="{{ route('rintereses.index') }}">
                            <i class="fa fa-circle-o"></i><span>Intereses generados</span>
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

            <li  class="{{ in_array(Request::segment(1), ['reporteverextractos']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i> Documentación <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reporteverextractos']) ? 'active' : '' }}">
                        <a href="{{ route('reporteverextractos.index') }}">
                            <i class="fa fa-circle-o"></i><span>Ver extractos</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['reportesabanacobros']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-globe"></i> <span>Comercial</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Reportes inventario --}}
            <li class="{{ in_array(Request::segment(1), ['reportesabanacobros']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> Reportes <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array(Request::segment(1), ['reportesabanacobros']) ? 'active' : '' }}">
                        <a href="{{ route('reportesabanacobros.index') }}">
                            <i class="fa fa-circle-o"></i><span>Sabana de ventas costos</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview {{ in_array(Request::segment(1), ['presupuestosg', 'reportearp']) ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-book"></i> <span>Contabilidad</span><i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
            {{-- Modulos Contabilidad --}}
            <li class="{{ in_array(Request::segment(1), ['presupuestosg']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-wpforms"></i> Módulos <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::segment(1) == 'presupuestosg' ? 'active' : '' }}">
                        <a href="{{ route('presupuestosg.index') }}"><i class="fa fa-wrench"></i> Presupuestos</a>
                    </li>
                </ul>
            </li>

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
