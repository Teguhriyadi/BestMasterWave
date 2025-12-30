<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin-panel/dashboard') }}">
        <div class="sidebar-brand-text mx-3">
            Admin Panel BMW
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

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

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ Request::is('admin-panel/platform') || Request::is('admin-panel/seller') || Request::is('admin-panel/supplier') || Request::is('admin-panel/bank') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-book"></i>
            <span>Master</span>
        </a>
        <div id="collapseTwo" class="collapse {{ Request::is('admin-panel/platform') || Request::is('admin-panel/seller') || Request::is('admin-panel/supplier') || Request::is('admin-panel/bank') ? 'show' : '' }} " aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/platform') ? 'active' : '' }}" href="{{ url('/admin-panel/platform') }}">Platform</a>
                <a class="collapse-item {{ Request::is('admin-panel/seller') ? 'active' : '' }}" href="{{ url('/admin-panel/seller') }}">Seller</a>
                <a class="collapse-item {{ Request::is('admin-panel/bank') ? 'active' : '' }}" href="{{ url('/admin-panel/bank') }}">Bank</a>
                <a class="collapse-item {{ Request::is('admin-panel/supplier') ? 'active' : '' }}" href="{{ url('/admin-panel/supplier') }}">Supplier</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master Transaksi
    </div>

    <li class="nav-item {{ Request::is('admin-panel/shopee/pendapatan') || Request::is('admin-panel/shopee/pesanan') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShopee"
            aria-expanded="true" aria-controls="collapseShopee">
            <i class="fas fa-fw fa-book"></i>
            <span>Shopee</span>
        </a>
        <div id="collapseShopee" class="collapse {{ Request::is('admin-panel/shopee/pendapatan*') || Request::is('admin-panel/shopee/pesanan*') ? 'show' : '' }} " aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/shopee/pendapatan*') ? 'active' : '' }}" href="{{ url('/admin-panel/shopee/pendapatan') }}">Pendapatan</a>
                <a class="collapse-item {{ Request::is('admin-panel/shopee/pesanan*') ? 'active' : '' }}" href="{{ url('/admin-panel/shopee/pesanan') }}">Pesanan</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master User
    </div>

    <li class="nav-item {{ Request::is('admin-panel/shopee/pendapatan') || Request::is('admin-panel/shopee/pesanan') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengaturan"
            aria-expanded="true" aria-controls="collapsePengaturan">
            <i class="fas fa-fw fa-book"></i>
            <span>Pengaturan</span>
        </a>
        <div id="collapsePengaturan" class="collapse {{ Request::is('admin-panel/role*') || Request::is('admin-panel/role*') ? 'show' : '' }} " aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('admin-panel/role*') ? 'active' : '' }}" href="{{ url('/admin-panel/role') }}">Role</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
