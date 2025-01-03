<li class="nav-item">
    <a href="{{ url('home') }}" class="nav-link {{ setActive('home') }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Inicio
        </p>
    </a>
</li>
<li class="nav-header">CONTACTOS</li>
<li class="nav-item">
    <a href="{{ url('contactos') }}" class="nav-link {{ setActive('contactos') }}">
        <i class="nav-icon fas fa-address-book"></i>
        <p>Contactos</p>
    </a>
</li>
<li class="nav-header">SERVICIOS</li>
<li class="nav-item">
    <a href="{{ url('servicios') }}" class="nav-link {{ setActive('servicios') }}">
        <i class="nav-icon fas fa-tint"></i>
        <p>Servicios</p>
    </a>
</li>
<li class="nav-header">DOCUMENTACIÓN</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-file-archive"></i>
        <p>
            Documentos
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('auditoria-interna') }}" class="nav-link {{ setActive('auditoria-interna') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Auditoria Interna</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('leyes') }}" class="nav-link {{ setActive('leyes') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Reglamentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('ley-transparencia') }}" class="nav-link {{ setActive('ley-transparencia') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Ley de Transparencia</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('lotaip') }}" class="nav-link {{ setActive('lotaip') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>LOTAIP</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('lotaip-v2') }}" class="nav-link {{ setActive('lotaip-v2') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>LOTAIP 2.0</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('medios-verificacion') }}" class="nav-link {{ setActive('medios-verificacion') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Medios de Verificación</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('pac') }}" class="nav-link {{ setActive('pac') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>PAC</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('poa') }}" class="nav-link {{ setActive('poa') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>POA</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('pliego-tarifario') }}" class="nav-link {{ setActive('pliego-tarifario') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Pliego Tarifario</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('proceso-contratacion') }}" class="nav-link {{ setActive('proceso-contratacion') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Procesos SERCOP</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('rendicion-cuentas') }}" class="nav-link {{ setActive('rendicion-cuentas') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Rendición de Cuentas</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ url('docadministrativo') }}" class="nav-link {{ setActive('docadministrativo') }}">
        <i class="nav-icon fas fa-file-invoice"></i>
        <p>Doc. Administrativa</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('docfinanciero') }}" class="nav-link {{ setActive('docfinanciero') }}">
        <i class="nav-icon fas fa-file-invoice-dollar"></i>
        <p>Doc. Financiera</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('docoperativo') }}" class="nav-link {{ setActive('docoperativo') }}">
        <i class="nav-icon fas fa-hand-holding-water"></i>
        <p>Doc. Operativa</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('doclaboral') }}" class="nav-link {{ setActive('doclaboral') }}">
        <i class="nav-icon fas fa-id-card-alt"></i>
        <p>Doc. Laboral</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('library-externo') }}" class="nav-link {{ setActive('library-externo') }}">
        <i class="nav-icon fas fa-folder"></i>
        <p>Biblioteca Virtual</p>
    </a>
</li>
<li class="nav-header">AJUSTES</li>
<li class="nav-item">
    <a href="{{ url('banner') }}" class="nav-link {{ setActive('banner') }}">
        <i class="nav-icon far fa-images"></i>
        <p>Banner de Imágenes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('settings_infor_detaill_cuenta_view') }}" class="nav-link {{ setActive('settings_infor_detaill_cuenta_view') }}">
        <i class="nav-icon far fa-images"></i>
        <p>Infor. Cuenta</p>
    </a>
</li>
