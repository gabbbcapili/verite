@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', strtoupper(str_replace('_', ' ', $type))  .' Template')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
@can('template.manage')
<section id="card-actions">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Create New Template</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <form action="{{ route('template.spaf.store') }}" method="POST" class="form" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="type", value="{{ $type }}">
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Template Name</label>
                          <input type="text" class="form-control" name="name" placeholder="Template Name">
                      </div>
                    </div>
                    @if(in_array($type, App\Models\Template::$forAudit))
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Audit Template Type</label>
                          <select type="text" class="form-control select2" name="audit_type">
                            <option disabled selected></option>
                            @foreach(App\Models\Template::$auditTypes as $auditType => $permission)
                              <option value="{{ $auditType }}">{{ $auditType }}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="row">
                    <div class="col-6">
                     <div class="col-12">
                          <input type="submit" name="save" class="btn btn-primary mr-1 mb-1 btn_save" value="Save">
                          <!-- <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset </button> -->
                      </div>
                    </div>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endcan
<section id="basic-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <table class="datatables-basic table" id="spaf_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Name</th>
              @if(in_array($type, App\Models\Template::$forAudit))
              <th>Audit Template Type</th>
              @endif
              <th>Active</th>
              <th>Status</th>
              <th>Approved By</th>
              <th>Created</th>
              <th>Updated</th>
              <th class="noexport">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>
<!--/ Basic table -->
@endsection

@section('vendor-script')
  {{-- vendor files --}}
  <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/jszip.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
  <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
    $('.select2').select2();
    var table_id = 'spaf_table'
    var table_title = 'Template List';
    var table_route = {
          url: '{{ route('template.spaf.index', ['type' => $type]) }}',
          data: function (data) {
                // data.field = $("#field").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'name', name: 'name'},
            @if(in_array($type, App\Models\Template::$forAudit))
            { data: 'audit_type', name: 'audit_type'},
            @endif
            { data: 'statusText', name: 'status'},
            { data: 'is_approved', name: 'is_approved'},
            { data: 'approved_by', name: 'approved_by'},
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', name: 'action', 'orderable' : false}
        ];
      // var buttons = [
      //       {
      //           text: '<i data-feather="plus"></i> Create New',
      //           className: 'btn btn-primary',
      //           action: function ( e, dt, node, config ) {
      //               window.location.href = '{{ route("template.spaf.create") }}';
      //           }
      //       }
      //   ];
      var drawCallback = function( settings ) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        feather.replace({
          width: 14,height: 14
        });
      };
      var order =  [[ 0, "desc" ]];
  </script>
  <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
