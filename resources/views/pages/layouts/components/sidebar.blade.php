<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin-panel/dashboard') }}">
        <div class="sidebar-brand-text mx-3">
            Admin Panel BMW
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if (!empty(Auth::user()->one_divisi_roles))
        <div class="sidebar-heading mt-3 mb-3 text-white fw-bold">
            Divisi :
            <br>
            <span style="font-size: 14px">
                {{ Auth::user()->one_divisi_roles->divisi->nama_divisi }}
            </span>
        </div>
        <hr class="sidebar-divider">
    @endif

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('admin-panel/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/admin-panel/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <li
        class="nav-item {{ Request::is('admin-panel/platform') || Request::is('admin-panel/seller') || Request::is('admin-panel/supplier') || Request::is('admin-panel/bank') || Request::is('admin-panel/barang') || Request::is('admin-panel/karyawan*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-book"></i>
            <span>Master</span>
        </a>
        <div id="collapseTwo"
            class="collapse {{ Request::is('admin-panel/platform') || Request::is('admin-panel/seller') || Request::is('admin-panel/supplier') || Request::is('admin-panel/bank') || Request::is('admin-panel/barang') || Request::is('admin-panel/jabatan') || Request::is('admin-panel/karyawan*') ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/platform') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/platform') }}">Platform</a>
                <a class="collapse-item {{ Request::is('admin-panel/jabatan') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/jabatan') }}">Jabatan</a>
                <a class="collapse-item {{ Request::is('admin-panel/karyawan*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/karyawan') }}">Karyawan</a>
                <a class="collapse-item {{ Request::is('admin-panel/seller') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/seller') }}">Seller</a>
                @if (empty(Auth::user()?->one_divisi_roles))
                    <a class="collapse-item {{ Request::is('admin-panel/bank') ? 'active' : '' }}"
                        href="{{ url('/admin-panel/bank') }}">Bank</a>
                @endif
                <a class="collapse-item {{ Request::is('admin-panel/supplier') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/supplier') }}">Supplier</a>
                <a class="collapse-item {{ Request::is('admin-panel/barang') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/barang') }}">Barang</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master Transaksi
    </div>

    <li class="nav-item {{ Request::is('admin-panel/pembelian') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePembelian"
            aria-expanded="true" aria-controls="collapsePembelian">
            <i class="fas fa-fw fa-book"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapsePembelian" class="collapse {{ Request::is('admin-panel/pembelian*') ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/pembelian*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/pembelian') }}">Pembelian</a>
            </div>
        </div>
    </li>

    <li
        class="nav-item {{ Request::is('admin-panel/shopee/pendapatan') || Request::is('admin-panel/shopee/pesanan') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShopee"
            aria-expanded="true" aria-controls="collapseShopee">
            <i class="fas fa-fw fa-book"></i>
            <span>Shopee</span>
        </a>
        <div id="collapseShopee"
            class="collapse {{ Request::is('admin-panel/shopee/pendapatan*') || Request::is('admin-panel/shopee/pesanan*') ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/shopee/pendapatan*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/shopee/pendapatan') }}">Pendapatan</a>
                <a class="collapse-item {{ Request::is('admin-panel/shopee/pesanan*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/shopee/pesanan') }}">Pesanan</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master Menu
    </div>

    <li
        class="nav-item {{ Request::is('admin-panel/permissions') || Request::is('admin-panel/role-permissions*') || Request::is('admin-panel/role') || Request::is('admin-panel/divisi') || Request::is('admin-panel/divisi-role*') || Request::is('admin-panel/users*') || Request::is('admin-panel/profil-saya*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKelolaMenu"
            aria-expanded="true" aria-controls="collapseKelolaMenu">
            <i class="fas fa-fw fa-book"></i>
            <span>Kelola Menu</span>
        </a>
        <div id="collapseKelolaMenu"
            class="collapse {{ Request::is('admin-panel/permissions*') || Request::is('admin-panel/role-permissions*') || Request::is('admin-panel/menu*') ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/menu*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/menu') }}">
                    Menu
                </a>
                {{-- <a class="collapse-item {{ Request::is('admin-panel/role-menu*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/role-menu') }}">
                    Role Menu
                </a> --}}
                <a class="collapse-item {{ Request::is('admin-panel/permissions*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/permissions') }}">
                    Permissions
                </a>
                <a class="collapse-item {{ Request::is('admin-panel/role-permissions*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/role-permissions') }}">
                    Role Permissions
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master User
    </div>

    <li
        class="nav-item {{ Request::is('admin-panel/role') || Request::is('admin-panel/divisi') || Request::is('admin-panel/divisi-role*') || Request::is('admin-panel/users*') || Request::is('admin-panel/profil-saya*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengaturan"
            aria-expanded="true" aria-controls="collapsePengaturan">
            <i class="fas fa-fw fa-book"></i>
            <span>Pengaturan</span>
        </a>
        <div id="collapsePengaturan"
            class="collapse {{ Request::is('admin-panel/role') || Request::is('admin-panel/divisi*') || Request::is('admin-panel/divisi-role*') || Request::is('admin-panel/users*') || Request::is('admin-panel/profil-saya*') ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/role') || Request::is('admin-panel/role/*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/role') }}">
                    Role
                </a>

                @if (!empty(Auth::user()->one_divisi_roles))
                @else
                    <a class="collapse-item {{ Request::is('admin-panel/divisi') ? 'active' : '' }}"
                        href="{{ url('/admin-panel/divisi') }}">Divisi</a>
                @endif
                <a class="collapse-item {{ Request::is('admin-panel/divisi-role*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/divisi-role') }}">Role Divisi</a>
                <a class="collapse-item {{ Request::is('admin-panel/users*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/users') }}">Users</a>
                <a class="collapse-item {{ Request::is('admin-panel/profil-saya*') ? 'active' : '' }}"
                    href="{{ url('/admin-panel/profil-saya/' . Auth::id()) }}">Profil saya</a>
            </div>
        </div>
    </li>

    @foreach ($sidebarMenus->where('type', 'menu')->whereNull('parent_id') as $menu)
        <li class="nav-item {{ Request::is($menu->url_menu . '*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url($menu->url_menu) }}">
                <i class="{{ $menu->icon }}"></i>
                <span>{{ $menu->nama_menu }}</span>
            </a>
        </li>
    @endforeach

    @foreach ($sidebarMenus->where('type', 'header') as $header)
        <hr class="sidebar-divider">
        <div class="sidebar-heading">{{ $header->nama_menu }}</div>

        @foreach ($sidebarMenus->where('parent_id', $header->id)->where('type', 'menu') as $menu)
            @if ($menu->children->count())
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#menu-{{ $menu->id }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->nama_menu }}</span>
                    </a>

                    <div id="menu-{{ $menu->id }}" class="collapse">
                        <div class="bg-white py-2 collapse-inner rounded">
                            @foreach ($menu->children as $sub)
                                <a class="collapse-item {{ Request::is($sub->url_menu . '*') ? 'active' : '' }}"
                                    href="{{ url($sub->url_menu) }}">
                                    {{ $sub->nama_menu }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </li>
            @else
                <li class="nav-item {{ Request::is($menu->url_menu . '*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url($menu->url_menu) }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->nama_menu }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    @endforeach

    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
