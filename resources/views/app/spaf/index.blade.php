@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Assessments')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
<section id="basic-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        @can('spaf.manage')
        <div class="card-body">
          <div class="row">
            <div class="col-3">
              <div class="form-group">
                <select class="form-control select2 selectFilter" id="status">
                  <option value="all">ALL STATUS</option>
                  <option value="pending">Pending</option>
                  <option value="answered">Waiting for Admin Approval</option>
                  <option value="additional">Additional Info Needed</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        @endcan
        <table class="datatables-basic table" id="assessment_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Client</th>
              <th>Client CP</th>
              <th>Supplier</th>
              <th>Supplier CP</th>
              <th>Template</th>
              <th>Type</th>
              <th>Status</th>
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
    var table_id = 'assessment_table'
    var table_title = 'Assessment List';
    var url = '{{ route('spaf.index') }}';
    @if($request->user()->hasRole('Supplier'))
      url = '{{ route('spaf.supplierIndex') }}';
    @elseif($request->user()->hasRole('Client'))
      url = '{{ route('spaf.clientIndex') }}';
    @endif
    var table_route = {
          url: url,
          data: function (data) {
                data.status = $("#status").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'clientCompanyName', name: 'clientCompanyName'},
            { data: 'clientName', name: 'clientName'},
            { data: 'supplierCompanyName', name: 'supplierCompanyName'},
            { data: 'supplierName', name: 'supplierName'},
            { data: 'templateName', name: 'templateName'},
            { data: 'type', name: 'type'},
            { data: 'status', name: 'status'},
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', name: 'action', 'orderable' : false}
        ];
      @if($request->user()->can('spaf.manage'))
      var buttons = [
            {
                text: '<i data-feather="printer"></i> Print',
                extend: 'print',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':not(.noexport)'
                }
            },
            {
              text: '<i data-feather="file"></i> Excel',
                extend: 'excel',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':not(.noexport)'
                }
            },
            {
                text: '<i data-feather="file-text"></i> PDF',
                extend: 'pdf',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':not(.noexport)'
                }
            },
            {
                text: '<i data-feather="plus"></i> Create New',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location.href = '{{ route("spaf.create") }}';
                }
            }
        ];
      @endif
      var drawCallback = function( settings ) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        feather.replace({
          width: 14,height: 14
        });
      };
      $(document).ready(function(){
        $('.select2').select2();
      });
  </script>
  <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
