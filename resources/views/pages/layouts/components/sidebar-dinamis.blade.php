<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin-panel/dashboard') }}">
        <div class="sidebar-brand-text mx-3">
            Admin Panel BMW
        </div>
    </a>

    <hr class="sidebar-divider sidebar-divider-item my-0">

    <li class="nav-item px-3 mt-3">
        <input type="text" id="sidebarSearch" class="form-control form-control-sm" placeholder="Cari menu..."
            autocomplete="off">
    </li>

    @if (!empty(Auth::user()->one_divisi_roles))
        <div class="sidebar-heading mt-3 mb-3 text-white fw-bold">
            Divisi :
            <br>
            <span style="font-size: 14px">
                {{ Auth::user()->one_divisi_roles->divisi->nama_divisi }}
            </span>
        </div>
        <hr class="sidebar-divider sidebar-divider-item">
    @endif

    <li class="nav-item {{ Request::is('admin-panel/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/admin-panel/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @foreach ($sidebarMenus->where('type', 'header') as $header)
        <hr class="sidebar-divider sidebar-divider-item">
        <div class="sidebar-heading sidebar-header">
            {{ $header->nama_menu }}
        </div>

        @foreach ($sidebarMenus->where('parent_id', $header->id)->where('type', 'menu') as $menu)
            @php
                $submenus = $sidebarMenus->where('parent_id', $menu->id)->where('type', 'submenu');
            @endphp

            @if ($submenus->count() > 0)
                <li class="nav-item sidebar-menu-item {{ isOpen($submenus) ? 'active' : '' }}">
                    <a class="nav-link {{ isOpen($submenus) ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                        data-target="#menu-{{ $menu->id }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->nama_menu }}</span>
                    </a>

                    <div id="menu-{{ $menu->id }}" class="collapse {{ isOpen($submenus, true) }}">
                        <div class="bg-white py-2 collapse-inner rounded">
                            @foreach ($submenus as $sub)
                                <a class="collapse-item sidebar-submenu-item {{ isActive('admin-panel/' . $sub->url_menu) ? 'active' : '' }}"
                                    href="{{ url('/admin-panel/' . $sub->url_menu) }}">
                                    {{ $sub->nama_menu }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </li>
            @else
                <li
                    class="nav-item sidebar-menu-item
                {{ Request::is('admin-panel/' . $menu->url_menu . '*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/admin-panel/' . $menu->url_menu) }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->nama_menu }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    @endforeach

    @if (empty(Auth::user()->one_divisi_roles) || Auth::user()->one_divisi_roles)
        @if (empty(Auth::user()->one_divisi_roles) || Auth::user()->one_divisi_roles->roles->nama_role == 'Super Admin')
            <hr class="sidebar-divider sidebar-divider-item">

            <div class="sidebar-heading sidebar-header">
                Master Menu
            </div>

            <li
                class="nav-item {{ Request::is('admin-panel/permissions') || Request::is('admin-panel/role-permissions*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKelolaMenu"
                    aria-expanded="true" aria-controls="collapseKelolaMenu">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Kelola Menu</span>
                </a>
                <div id="collapseKelolaMenu"
                    class="collapse {{ Request::is('admin-panel/permissions*') || Request::is('admin-panel/role-permissions*') || Request::is('admin-panel/menu*') ? 'show' : '' }} "
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if (empty(Auth::user()->one_divisi_roles))
                            @include('pages.layouts.components.sidebar-admin-menu')
                        @endif
                        <a class="collapse-item {{ Request::is('admin-panel/role-permissions*') ? 'active' : '' }}"
                            href="{{ url('/admin-panel/role-permissions') }}">
                            Role Permissions
                        </a>
                    </div>
                </div>
            </li>
        @endif
    @endif

    <hr class="sidebar-divider sidebar-divider-item">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
