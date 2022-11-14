@inject('request', 'Illuminate\Http\Request')
<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
  <li class="nav-item {{ $request->segment(1) == '' && $request->segment(2) == '' ? 'active' : '' }}">
      <a href="/" class="nav-link d-flex align-items-center">
        <i data-feather="home"></i>
        <span>Home</span>
      </a>
  </li>
  @can('template.manage')
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="file"></i>
    <span class="menu-title text-truncate">Templates</span></a>
    <ul class="menu-content">
      <li class="nav-item {{ $request->segment(1) == 'template' && $request->segment(2) == 'spaf' ? 'active' : '' }}">
        <a class="d-flex align-items-center" href="{{ route('template.spaf.index') }}"><i data-feather="circle"></i>
        <span class="menu-item text-truncate">SPAF</span></a>
      </li>
    </ul>
  </li>
  @endcan
  @if( $request->user()->can('user.manage') || $request->user()->can('supplier.manage') || $request->user()->can('role.manage'))
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="users"></i>
    <span class="menu-title text-truncate">Users</span></a>
    <ul class="menu-content">
      @can('user.manage')
      <li class="nav-item {{ $request->segment(1) == 'user' && $request->segment(2) == '' ? 'active' : '' }}">
        <a class="d-flex align-items-center" href="{{ route('user.index') }}"><i data-feather="align-justify"></i>
        <span class="menu-item text-truncate">List Users</span></a>
      </li>
      @endcan

      @can('role.manage')
      <li class="nav-item {{ $request->segment(1) == 'role' && $request->segment(2) == '' ? 'active' : '' }}">
        <a class="d-flex align-items-center" href="{{ route('role.index') }}"><i data-feather="circle"></i>
        <span class="menu-item text-truncate">Roles & Privileges</span></a>
      </li>
      @endcan
    </ul>
  </li>
  @endif
  @can('supplier.manage')
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="users"></i>
    <span class="menu-title text-truncate">Suppliers</span></a>
    <ul class="menu-content">
      @can('supplier.manage')
        <li class="nav-item {{ $request->segment(1) == 'supplier' && $request->segment(2) == '' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('supplier.index') }}"><i data-feather="align-justify"></i>
          <span class="menu-item text-truncate">List Suppliers</span></a>
        </li>
      @endcan

      @can('supplier.manage')
        <li class="nav-item {{ $request->segment(1) == 'supplier' && $request->segment(2) == 'create' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('supplier.create') }}"><i data-feather="plus-circle"></i>
          <span class="menu-item text-truncate">Add Supplier</span></a>
        </li>
      @endcan
    </ul>
  </li>
  @endcan

  @if($request->user()->hasRole('Supplier'))
  <li class="nav-item {{ $request->segment(1) == 'spaf' ? 'active' : '' }}">
      <a href="{{ route('spaf.show', $request->user()->spaf) }}" class="nav-link d-flex align-items-center">
        <i data-feather="package"></i>
        <span>SPAF</span>
      </a>
  </li>
  @endif


</ul>



