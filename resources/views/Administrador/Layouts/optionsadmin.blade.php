<li class="nav-item">
    <a href="{{ url('home') }}" class="nav-link {{ setActive('home') }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Inicio
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-newspaper"></i>
        <p>
            Noticias
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('registrar_noticia') }}" class="nav-link {{ setActive('registrar_noticia') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Registrar Noticia</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('listado-noticias') }}" class="nav-link {{ setActive('listado-noticias') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de Noticias</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ url('eventos') }}" class="nav-link {{ setActive('eventos') }}">
        <i class="nav-icon far fa-calendar-alt"></i>
        <p>
            Eventos
        </p>
    </a>
</li>
<li class="nav-header">CONTACTOS</li>
<li class="nav-item">
    <a href="{{ url('red-social') }}" class="nav-link {{ setActive('red-social') }}">
        <i class="nav-icon fas fa-icons"></i>
        <p>Redes Sociales</p>
    </a>
</li>
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
    <a href="{{ url('anio') }}" class="nav-link {{ setActive('anio') }}">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>Años</p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-list-ul"></i>
        <p>
            Items LOTAIP
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('articles-lotaip') }}" class="nav-link {{ setActive('articles-lotaip') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Artículos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('setting-lotaip') }}" class="nav-link {{ setActive('setting-lotaip') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Literales</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('options-lotaip') }}" class="nav-link {{ setActive('options-lotaip') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Opciones Adicionales</p>
            </a>
        </li>
    </ul>
</li>
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
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-info-circle"></i>
        <p>
            La Institución
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('logo-institucion') }}" class="nav-link {{ setActive('logo-institucion') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Logo</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('about') }}" class="nav-link {{ setActive('about') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Acerca de</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('mi-vi-va-ob') }}" class="nav-link {{ setActive('mi-vi-va-ob') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Misión</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('estructura') }}" class="nav-link {{ setActive('estructura') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Estructura</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('historia') }}" class="nav-link {{ setActive('historia') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Historia</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('departamentos') }}" class="nav-link {{ setActive('departamentos') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Departamentos</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ url('perfil-usuario') }}" class="nav-link {{ setActive('perfil-usuario') }}">
        <i class="nav-icon fas fa-user-cog"></i>
        <p>Perfil de Usuario</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ url('usuarios') }}" class="nav-link {{ setActive('usuarios') }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Usuarios</p>
    </a>
</li>
