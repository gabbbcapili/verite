@inject('request', 'Illuminate\Http\Request')

<div class="card">
  <div class="card-header">
    <h4 class="card-title">Quick Links</h4>
  </div>
  <div class="card-body">
    <div class="row g-1">
      @if( $request->user()->can('user.manage') || $request->user()->can('role.manage') || $request->user()->can('client.manage') || $request->user()->can('supplier.manage') || $request->user()->can('template.manage') || $request->user()->can('template.approve') || $request->user()->can('spaf.manage') || $request->user()->can('spaf.approve') || $request->user()->can('audit.manage') || $request->user()->can('schedule.selectableAuditor')  || $request->user()->can('report.manage') || $request->user()->can('report.manage_assigned_resource'))

        @if( $request->user()->can('user.manage') || $request->user()->can('role.manage'))
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bmGreen cwhite quick-button small" href="{{ route('user.index') }}">
                <i data-feather="users" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Users</h5>
            </a>
        </div>
        @endif
        @can('client.manage')
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bred cwhite quick-button small" href="{{ route('client.index') }}">
                <i data-feather="award" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Clients</h5>
            </a>
        </div>
        @endcan
        @can('supplier.manage')
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="borange cwhite quick-button small" href="{{ route('supplier.index') }}">
                <i data-feather="package" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Suppliers</h5>
            </a>
        </div>
        @endcan
        @if( $request->user()->can('template.manage') || $request->user()->can('template.approve'))
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bblue cwhite quick-button small" href="{{ route('template.spaf.index', ['type' => 'spaf']) }}">
                <i data-feather="file" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Templates</h5>
            </a>
        </div>
        @endif
        @if( $request->user()->can('spaf.manage') || $request->user()->can('spaf.approve'))
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="byellow cwhite quick-button small" href="{{ route('spaf.index') }}">
                <i data-feather="package" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Assessments</h5>
            </a>
        </div>
        @endif

        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bpink cwhite quick-button small" href="{{ route('schedule.index') }}">
                <i data-feather="calendar" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Schedules</h5>
            </a>
        </div>

        @if($request->user()->can('audit.manage') || $request->user()->can('schedule.selectableAuditor'))
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="blightBlue cwhite quick-button small" href="{{ route('audit.index') }}">
                <i data-feather="folder" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Audits</h5>
            </a>
        </div>
        @endif

        @if($request->user()->can('report.manage') || $request->user()->can('report.manage_assigned_resource'))
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bgreen cwhite quick-button small" href="{{ route('report.index') }}">
                <i data-feather="file-text" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Reports</h5>
            </a>
        </div>
        @endif
      @endif


        @if($request->user()->hasRole('Client'))
          <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="borange cwhite quick-button small" href="{{ route('spaf.clientIndex') }}">
                <i data-feather="columns" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Assessment Forms</h5>
            </a>
        </div>
        @endif

        @if($request->user()->hasRole('Supplier'))
          <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="borange cwhite quick-button small" href="{{ route('spaf.supplierIndex') }}">
                <i data-feather="columns" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Assessment Forms</h5>
            </a>
        </div>
        @endif


    </div>
  </div>
</div>
