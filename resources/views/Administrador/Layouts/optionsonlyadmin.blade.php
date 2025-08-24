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