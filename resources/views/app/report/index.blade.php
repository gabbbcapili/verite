@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Reports')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
<section id="basic-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        @can('audit.manage')
<!--         <div class="card-body">
          <div class="row">
            <div class="col-3">
              <div class="form-group">
                <select class="form-control select2 selectFilter" id="status">
                  <option value="all">ALL STATUS</option>
                  <option value="pending">Pending</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
            </div>
          </div>
        </div> -->
        @endcan
        <table class="datatables-basic table" id="reportTable">
          <thead>
            <tr>
              <th>Id</th>
              <th>Title</th>
              <th>Audit</th>
              <th>Schedule</th>
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

    var table_id = 'reportTable'
    var table_title = 'Report List';
    var table_route = {
          url: '{{ route('report.index') }}',
          data: function (data) {
                data.status = $("#status").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'title', name: 'title'},
            { data: 'audit_title', name: 'audit_title'},
            { data: 'schedule_title', name: 'schedule.title'},
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', name: 'action', 'orderable' : false, 'printable' : false}
        ];

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
            @can('report.manage')
            {
                text: '<i data-feather="plus"></i> Create New',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location.href = '{{ route("report.create") }}';
                }
            },
            @endif
        ];
      var drawCallback = function( settings ) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        feather.replace({
          width: 14,height: 14
        });
      };
      var order =  [[ 0, "desc" ]];
      $(document).ready(function(){
        $('.select2').select2();
      });
  </script>
  <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
