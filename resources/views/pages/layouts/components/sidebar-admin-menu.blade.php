<a class="collapse-item {{ Request::is('admin-panel/menu*') ? 'active' : '' }}" href="{{ url('/admin-panel/menu') }}">
    Menu
</a>
<a class="collapse-item {{ Request::is('admin-panel/permissions*') ? 'active' : '' }}"
    href="{{ url('/admin-panel/permissions') }}">
    Permissions
</a>
