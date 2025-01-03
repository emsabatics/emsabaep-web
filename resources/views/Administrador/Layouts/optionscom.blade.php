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
