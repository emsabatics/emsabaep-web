<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-list"></i>
        <p>
            Módulos Sistema
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('modulos') }}" class="nav-link {{ setActive('modulos') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Módulos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('submodulos') }}" class="nav-link {{ setActive('submodulos') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Submódulos</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ url('reportes-contador') }}" class="nav-link {{ setActive('reportes-contador') }}">
        <i class="nav-icon far fa-chart-bar"></i>
        <p>
            Contador de Visitas
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-chart-bar"></i>
        <p>Contador de Descargas<i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-admin') }}" class="nav-link {{ setActive('reportes-contador-descargas-admin') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Doc. Administrativa</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-fin') }}" class="nav-link {{ setActive('reportes-contador-descargas-fin') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Doc. Financiera</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-opt') }}" class="nav-link {{ setActive('reportes-contador-descargas-opt') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Doc. Operativa</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-lab') }}" class="nav-link {{ setActive('reportes-contador-descargas-lab') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Doc. Laboral</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-ley') }}" class="nav-link {{ setActive('reportes-contador-descargas-ley') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Reglamentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-rendicionc') }}" class="nav-link {{ setActive('reportes-contador-descargas-rendicionc') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Rendición de Cuentas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-lotaipv1') }}" class="nav-link {{ setActive('reportes-contador-descargas-lotaipv1') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>LOTAIP</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-lotaipv2') }}" class="nav-link {{ setActive('reportes-contador-descargas-lotaipv2') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>LOTAIP V2</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-auditoria') }}" class="nav-link {{ setActive('reportes-contador-descargas-auditoria') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Auditoría Interna</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('reportes-contador-descargas-bvirtual') }}" class="nav-link {{ setActive('reportes-contador-descargas-bvirtual') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Biblioteca Virtual</p>
            </a>
        </li>
    </ul>
</li>