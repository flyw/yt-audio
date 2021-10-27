<li class="nav-item">
    <a class="nav-link {{ active_class(Active::checkUriPattern('channels*')) }}" href="{{ route('channels.index') }}">
        <i class="nav-icon fas fa-info-circle"></i>Channels
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ active_class(Active::checkUriPattern('entities*')) }}" href="{{ route('entities.index') }}">
        <i class="nav-icon fas fa-info-circle"></i>Entities
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ active_class(Active::checkUriPattern('downloads*')) }}" href="{{ route('downloads.index') }}">
        <i class="nav-icon fas fa-info-circle"></i>Downloads
    </a>
</li>
