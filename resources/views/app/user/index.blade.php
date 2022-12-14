@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'User Management')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
<section id="card-actions">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Create New User</h4>
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
            <form action="{{ route('user.store') }}" method="POST" class="form" enctype="multipart/form-data">
              @csrf
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">First Name:</label>
                          <input type="text" class="form-control" name="first_name" placeholder="First Name">
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Last Name:</label>
                          <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Email:</label>
                          <input type="text" class="form-control" name="email" placeholder="Email">
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Role:</label>
                          <select class="form-control select2" name="role">
                            @foreach($roles as $role)
                              <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                     <div class="col-12">
                          <input type="submit" name="save_with_reload_table" class="btn btn-primary mr-1 mb-1 btn_save" value="Save">
                          <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Clear </button>
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
<section id="basic-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-3">
              <div class="form-group">
                <select class="form-control select2 selectFilter" id="role">
                  <option value="all">ALL ROLES</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <table class="datatables-basic table" id="user_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
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

    var table_id = 'user_table'
    var table_title = 'Users List';
    var table_route = {
          url: '{{ route('user.index') }}',
          data: function (data) {
                data.role = $("#role").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'fullName', name: 'fullName'},
            { data: 'email', name: 'email'},
            { data: 'role', name: 'role'},
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', name: 'action', 'orderable' : false, 'printable' : false}
        ];
      var drawCallback = function( settings ) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        feather.replace({
          width: 14,height: 14
        });
      };
      var order =  [[ 0, "desc" ]];
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
        ];

      $(document).ready(function(){
        $('.select2').select2();
      });
  </script>
  <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
