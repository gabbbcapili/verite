@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Clients')

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
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4 col-xs-12">
              <div class="form-group">
                  <label for="name">User:</label>
                  <select class="form-control select2 selectFilter" id="user">
                    <option disabled selected></option>
                    @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            @include('app.setting.country.entry')
          </div>
        </div>
        <table class="datatables-basic table" id="client_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Company</th>
              <th>Country</th>
              <th>State</th>
              <th class="noexport">Active Contact Persons</th>
              <th class="noexport">Inactive Contact Persons</th>
              <th>Active Contact Persons</th>
              <th>Inactive Contact Persons</th>
              <th class="noexport">Suppliers</th>
              <th>Suppliers</th>
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

    var table_id = 'client_table'
    var table_title = 'Client List';
    var table_route = {
          url: '{{ route('client.index') }}',
          data: function (data) {
                data.country = $("#country").val();
                data.state = $("#state").val();
                data.user = $("#user").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'company_display', name: 'company_name'},
            { data: 'country', name: 'country.name', 'orderable' : false},
            { data: 'state', name: 'state.name', 'orderable' : false},
            { data: 'contact_persons', name: 'contact_persons', 'orderable' : false},
            { data: 'contact_persons_inactive', name: 'contact_persons_inactive', 'orderable' : false},
            { data: 'contactPersonsExport', name: 'contactPersonsExport', visible: false},
            { data: 'contactPersonsInactiveExport', name: 'contactPersonsInactiveExport', visible: false},
            { data: 'suppliers', name: 'suppliers', 'orderable' : false},
            { data: 'suppliersExport', name: 'suppliersExport', visible: false},
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at', export: 'created_at'},
            { data: 'action', name: 'action', 'orderable' : false}
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
            {
                text: '<i data-feather="plus"></i> Create New',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location.href = '{{ route("client.create") }}';
                }
            },

        ];
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
