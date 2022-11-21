@inject('request', 'Illuminate\Http\Request')

<div class="card">
  <div class="card-header">
    <h4 class="card-title">Quick Links</h4>
  </div>
  <div class="card-body">
    <div class="row g-1">
      @if($request->user()->can('tempalte.manage') || $request->user()->can('user.manage') || $request->user()->can('supplier.manage') || $request->user()->can('client.manage'))
        @can('template.manage')
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bblue cwhite quick-button small" href="{{ route('template.spaf.index', ['type' => 'spaf']) }}">
                <i data-feather="file" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Templates</h5>
            </a>
        </div>
        @endcan
        @can('user.manage')
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="bmGreen cwhite quick-button small" href="{{ route('user.index') }}">
                <i data-feather="users" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Users</h5>
            </a>
        </div>
        @endcan
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
        @can('spaf.manage')
        <div class="col-lg-1 col-md-2 col-xs-6">
            <a class="byellow cwhite quick-button small" href="{{ route('spaf.index') }}">
                <i data-feather="package" class="feather-20 mb-50"></i>
                <h5 class="cwhite">Assessments</h5>
            </a>
        </div>
        @endcan
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
