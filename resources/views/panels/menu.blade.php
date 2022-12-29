@inject('request', 'Illuminate\Http\Request')
<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
  <li class="nav-item {{ $request->segment(1) == '' && $request->segment(2) == '' ? 'active' : '' }}">
      <a href="/" class="nav-link d-flex align-items-center">
        <i data-feather="home"></i>
        <span>Home</span>
      </a>
  </li>

  @if( $request->user()->can('user.manage') || $request->user()->can('role.manage'))
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





  @can('client.manage')
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="award"></i>
    <span class="menu-title text-truncate">Clients</span></a>
    <ul class="menu-content">
      @can('client.manage')
        <li class="nav-item {{ $request->segment(1) == 'client' && $request->segment(2) == '' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('client.index') }}"><i data-feather="align-justify"></i>
          <span class="menu-item text-truncate">List Clients</span></a>
        </li>
      @endcan

      @can('client.manage')
        <li class="nav-item {{ $request->segment(1) == 'client' && $request->segment(2) == 'create' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('client.create') }}"><i data-feather="plus-circle"></i>
          <span class="menu-item text-truncate">Create New Client</span></a>
        </li>
      @endcan
    </ul>
  </li>
  @endcan


  @can('supplier.manage')
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="package"></i>
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
          <span class="menu-item text-truncate">Create New Supplier</span></a>
        </li>
      @endcan
    </ul>
  </li>
  @endcan

  @if( $request->user()->can('template.manage') || $request->user()->can('template.approve'))
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="file"></i>
    <span class="menu-title text-truncate">Templates</span><span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_templates"></span></a>
    <ul class="menu-content">
      <li class="nav-item {{ $request->segment(1) == 'template' && $request->segment(2) == 'spaf' && $request->segment(3) == 'spaf' ? 'active' : '' }}">
        <a class="d-flex align-items-center" href="{{ route('template.spaf.index', ['type' => 'spaf']) }}"><i data-feather="circle"></i>
          <span class="menu-item text-truncate">SPAF</span>
          <span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_spaf"></span>
        </a>

      </li>
      <li class="nav-item {{ $request->segment(1) == 'template' && $request->segment(2) == 'spaf' && $request->segment(3) == 'spaf_extension' ? 'active' : '' }}">
        <a class="d-flex align-items-center" href="{{ route('template.spaf.index', ['type' => 'spaf_extension']) }}"><i data-feather="circle"></i>
          <span class="menu-item text-truncate">SPAF Extension</span>
          <span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_spaf_extension"></span>
        </a>

      </li>
      <li class="nav-item {{ $request->segment(1) == 'template' && $request->segment(2) == 'spaf' && $request->segment(3) == 'risk_management' ? 'active' : '' }}">
      <a class="d-flex align-items-center" href="{{ route('template.spaf.index', ['type' => 'risk_management']) }}"><i data-feather="circle"></i>
        <span class="menu-item text-truncate">Risk Management</span>
        <span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_risk_management"></span>
      </a>
      </li>
    </ul>
  </li>
  @endif

  @if( $request->user()->can('spaf.manage') || $request->user()->can('spaf.approve'))
  <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#">
    <i data-feather="columns"></i>
    <span class="menu-title text-truncate">Assessment Forms</span><span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_assessment_forms_admin"></span></a>
    <ul class="menu-content">
        <li class="nav-item {{ $request->segment(1) == 'spaf' && $request->segment(2) == '' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('spaf.index') }}"><i data-feather="align-justify"></i>
          <span class="menu-item text-truncate">List Assessments</span></a>
        </li>

      @can('spaf.manage')
        <li class="nav-item {{ $request->segment(1) == 'spaf' && $request->segment(2) == 'create' ? 'active' : '' }}">
          <a class="d-flex align-items-center" href="{{ route('spaf.create') }}"><i data-feather="plus-circle"></i>
          <span class="menu-item text-truncate">Create New Assessment</span></a>
        </li>
      @endcan
    </ul>
  </li>
  @endcan

  @can('setting.manage')
  <li class="nav-item {{ $request->segment(1) == 'settings' ? 'active' : '' }}">
      <a href="{{ route('settings.index') }}" class="nav-link d-flex align-items-center">
        <i data-feather="settings"></i>
        <span>Settings</span>
      </a>
  </li>
  @endcan




  @if($request->user()->hasRole('Client'))
  <li class="nav-item {{ $request->segment(1) == 'spaf' ? 'active' : '' }}">
      <a href="{{ route('spaf.clientIndex') }}" class="nav-link d-flex align-items-center">
        <i data-feather="columns"></i>
        <span>Assessment Forms</span><span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_assessment_forms"></span>
      </a>
  </li>
  @endif

  @if($request->user()->hasRole('Supplier'))
  <li class="nav-item {{ $request->segment(1) == 'spaf' ? 'active' : '' }}">
      <a href="{{ route('spaf.supplierIndex') }}" class="nav-link d-flex align-items-center">
        <i data-feather="columns"></i>
        <span>Assessment Forms</span><span class="badge badge-light-warning rounded-pill ms-auto me-1" id="badge_assessment_forms"></span>
      </a>
  </li>
  @endif


</ul>



