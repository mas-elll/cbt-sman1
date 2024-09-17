<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center my-3" href="/admin/">
        <div class="sidebar-brand-icon">
            <img src="/assets/logo.png" class="w-50 h-50" alt="">
        </div>
        <div class="sidebar-brand-text mx-3">CBT SMAN 1 Bantul</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @if (auth()->user()->hasRole('admin'))
    <li class="nav-item {{ Request::is('admin') ? 'active' : '' }}">
        <a class="nav-link" href="/admin/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endif
    @if (auth()->user()->hasRole('guru'))
    <li class="nav-item {{ Request::is('guru') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endif

    @if (auth()->user()->hasRole('admin'))
    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- PENGGUNA -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengguna</span>
        </a>
        <div id="collapseTwo" class="collapse {{ Request::is('admin/guru', 'admin/siswa', 'admin/tahun-ajaran') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Buat Pengguna:</h6>
                <a class="collapse-item {{ Request::is('admin/guru') ? 'active' : '' }}" href="/admin/guru">Guru</a>
                <a class="collapse-item {{ Request::is('admin/siswa') ? 'active' : '' }}" href="/admin/siswa">Siswa</a>
                <a class="collapse-item {{ Request::is('admin/tahun_ajaran') ? 'active' : '' }}" href="/admin/tahun_ajaran">Tahun Ajaran</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwoSiswa"
            aria-expanded="true" aria-controls="collapseTwoSiswa">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kelas & Mata Pelajaran</span>
        </a>
        <div id="collapseTwoSiswa" class="collapse {{ Request::is('admin/kelas', 'admin/mapel') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Buat Kelas & Mapel:</h6>
                <a class="collapse-item {{ Request::is('admin/kelas') ? 'active' : '' }}" href="/admin/kelas">Kelas</a>
                <a class="collapse-item {{ Request::is('admin/mapel') ? 'active' : '' }}" href="/admin/mapel">Mata Pelajaran</a>
            </div>
        </div>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Penugasan
    </div>

    <!-- Nav Item - penugasan -->
    @if (auth()->user()->hasRole('admin'))
    <li class="nav-item {{ Request::is('admin/lembar-soal') ? 'active' : '' }}">
        <a class="nav-link" href="/admin/pilih-tahun-ajaran">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Lembar Soal</span>
        </a>
    </li>
    @endif

    <!-- Nav Item - penugasan -->
    @if (auth()->user()->hasRole('guru'))
    <li class="nav-item {{ Request::is('guru/lembar-soal') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/lembar-soal">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Lembar Soal</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('guru/lembar-jawab') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/pilih-tahun-ajaran">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Lembar Jawab</span>
        </a>
    </li>
    @endif

    <!-- Nav Item - penugasan -->
    @if (auth()->user()->hasRole('siswa'))
    <li class="nav-item {{ Request::is('siswa/tugas') ? 'active' : '' }}">
        <a class="nav-link" href="/siswa/tugas">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Daftar Tugas Tersedia</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('siswa/lembar-soal') ? 'active' : '' }}">
        <a class="nav-link" href="/siswa/pilih-tahun-ajaran2">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Daftar Lembar Soal</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('siswa/laporan') ? 'active' : '' }}">
        <a class="nav-link" href="/siswa/pilih-tahun-ajaran1">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Laporan</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
