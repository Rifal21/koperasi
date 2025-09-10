 <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
     id="sidenav-main">
     <div class="sidenav-header">
         <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
             aria-hidden="true" id="iconSidenav"></i>
         <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
             <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
             <span class="ms-1 font-weight-bold">Koperasi Maju Jaya</span>
         </a>
     </div>
     <hr class="horizontal dark mt-0">
     <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
         <ul class="navbar-nav">
             <li class="nav-item">
                 <a class="nav-link {{ Route::current()->getName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                     <div
                         class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                         <i class="fas fa-tachometer-alt text-dark"></i>
                     </div>
                     <span class="nav-link-text ms-1">Dashboard</span>
                 </a>
             </li>
            <li class="nav-item mt-3">
                 <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pengelolaan Koperasi</h6>
             </li>
             <li class="nav-item">
                 <a class="nav-link {{ Route::current()->getName() == 'items.index' ? 'active' : '' }}" href="{{ route('items.index') }}">
                     <div
                         class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                         <i class="fas fa-warehouse text-dark"></i>
                     </div>
                     <span class="nav-link-text ms-1">Kelola Barang</span>
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link {{ Route::current()->getName() == 'suppliers.index' ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                     <div
                         class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                         <i class="fas fa-gifts text-dark"></i>
                     </div>
                     <span class="nav-link-text ms-1">Kelola Suplier</span>
                 </a>
             </li>
                <li class="nav-item">
                 <a class="nav-link {{ Route::current()->getName() == 'clients.index' ? 'active' : '' }}" href="{{ route('clients.index') }}">
                     <div
                         class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                         <i class="fas fa-users text-dark"></i>
                     </div>
                     <span class="nav-link-text ms-1">Kelola Client</span>
                 </a>
             </li>

             <li class="nav-item mt-3">
                 <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Transaksi & Laporan</h6>
             </li>

             <li class="nav-item">
                 <a class="nav-link {{ Route::current()->getName() == 'transactions.index' ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                     <div
                         class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                         <i class="fas fa-store text-dark"></i>
                     </div>
                     <span class="nav-link-text ms-1">Transaksi</span>
                 </a>
             </li>
         </ul>
     </div>
 </aside>
