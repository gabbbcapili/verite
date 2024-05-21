@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">

@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-invoice-list.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <style type="text/css">

  </style>
  @endsection


@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <!-- dashboard default -->
  @if($request->user()->can('dashboard.default') || $request->user()->can('dashboard.scheduler'))
    <div class="row match-height">
      <!-- Statistics Card -->
      @if(! $request->user()->hasRole('Default'))
      <div class="col-xl-8 col-md-6 col-12">
        <div class="card card-statistics">
          <div class="card-header">
            <h4 class="card-title">Statistics</h4>
            <div class="d-flex align-items-center">
              <p class="card-text font-small-2 me-25 mb-0"></p>
            </div>
          </div>
          <div class="card-body statistics-body">
            <div class="row">
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="d-flex flex-row">
                  <div class="avatar bg-light-warning me-2">
                    <div class="avatar-content">
                      <i data-feather="users" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="my-auto">
                    <h4 class="fw-bolder mb-0">{{ $totals['users'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Users</p>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                  <div class="avatar bg-light-info me-2">
                    <div class="avatar-content">
                      <i data-feather="award" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="my-auto">
                    <h4 class="fw-bolder mb-0">{{ $totals['clients'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Clients</p>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                  <div class="avatar bg-light-primary me-2">
                    <div class="avatar-content">
                      <i data-feather="package" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="my-auto">
                    <h4 class="fw-bolder mb-0">{{ $totals['suppliers'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Suppliers</p>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="d-flex flex-row">
                  <div class="avatar bg-light-success me-2">
                    <div class="avatar-content">
                      <i data-feather="columns" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="my-auto">
                    <h4 class="fw-bolder mb-0">{{ $totals['spafs'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Completed Assessments</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      <!--/ Statistics Card -->
    </div>

    <!-- Quick Links -->
    <div class="row">
      @include('app.dashboard.partials.quick_links')
    </div>
    <!-- Quick Links -->
      <!-- Schedules -->
    <div class="row">
      <div class="col-xl-8 col-md-6 col-12">
        <div class="card card-statistics">
          <div class="card-header">
            <h4 class="card-title">Schedules</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control select2FormattedColor selectFilter" id="scheduleStatus">
                    <option value="all">ALL STATUS</option>
                    @foreach($totals['scheduleStatus'] as $scheduleStatus)
                        <option value="{{ $scheduleStatus->name }}" data-color="{{ $scheduleStatus->color }}" data-id="{{ $scheduleStatus->id }}">
                            <span class="text-{{ $scheduleStatus->color }}">{{ $scheduleStatus->name }}</span>
                        </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                  <input type="text" name="start_end_date" class="form-control rangePick selectFilter" id="dateRange">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <table class="datatables-basic table" id="schedule_table">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Title</th>
                      <th>Status</th>
                      <th>Updated At</th>
                      <th>Person Days</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Schedules -->
    @if($request->user()->can('dashboard.scheduler'))
      <div class="row">
        <div class="col-xl-8 col-md-6 col-12">
          <div class="card card-statistics">
            <div class="card-header">
              <h4 class="card-title">Resource Schedules</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <select class="form-control select2 selectFilterResource" id="resourceStatus">
                      <option value="all">ALL STATUS</option>
                      <option value="0">Pending</option>
                      <option value="1">Accepted</option>
                      <option value="2">Rejected</option>
                    </select>
                  </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                    <input type="text" name="start_end_date" class="form-control rangePick selectFilterResource" id="resourcesDateRange">
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <table class="datatables-basic table" id="resource_schedules_table">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Schedule</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endif
  <!-- dashboard default -->


  @if($request->user()->can('dashboard.supplier') || $request->user()->can('dashboard.client'))
    <div class="row match-height">
      <!-- Greetings Card starts -->
    <div class="col-lg-6 col-md-12 col-sm-12">
      <div class="card card-congratulations">
        <div class="card-body text-center">
          <img
            src="{{asset('images/elements/decore-left.png')}}"
            class="congratulations-img-left"
            alt="card-img-left"
          />
          <img
            src="{{asset('images/elements/decore-right.png')}}"
            class="congratulations-img-right"
            alt="card-img-right"
          />
          <div class="avatar avatar-xl bg-primary shadow">
            <div class="avatar-content">
              <i data-feather="award" class="font-large-1"></i>
            </div>
          </div>
          <div class="text-center">
            <h1 class="mb-1 text-white">Welcome {{ $request->user()->fullname }}</h1>
            <p class="card-text m-auto w-75">

            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Greetings Card ends -->
    </div>
    <!-- Quick Links -->
    <div class="row">
      @include('app.dashboard.partials.quick_links')
    </div>
    <!-- Quick Links -->

    <section class="faq-contact">
    <div class="row pt-75">
      <div class="col-sm-6">
        <div class="card text-center faq-contact-card shadow-none py-1">
          <div class="accordion-body">
            <div class="avatar avatar-tag bg-light-primary mb-2 mx-auto">
              <i data-feather="phone-call" class="font-medium-3"></i>
            </div>
            <h4>+ (810) 2548 2568</h4>
            <span class="text-body">We are always happy to help!</span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card text-center faq-contact-card shadow-none py-1">
          <div class="accordion-body">
            <div class="avatar avatar-tag bg-light-primary mb-2 mx-auto">
              <i data-feather="mail" class="font-medium-3"></i>
            </div>
            <h4>hello@help.com</h4>
            <span class="text-body">Best way to get answer faster!</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endcan
</section>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/jszip.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
  <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
  @if($request->user()->can('dashboard.default') || $request->user()->can('dashboard.scheduler'))
    var table_id = 'schedule_table'
    var table_title = 'Audit Model List';
    var table_route = {
          url: '{{ route('home') }}',
          data: function (data) {
                data.scheduleStatus = $("#scheduleStatus").val();
                data.dateRange = $("#dateRange").val();
            }
        };
      var columnns = [
            { data: 'id', name: 'id'},
            { data: 'titleDisplay', name: 'title'},
            { data: 'statuses', name: 'statuses'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'person_days', name: 'person_days'},
        ];

      var buttons = [

        ];
      var drawCallback = function( settings ) {
        var api = this.api();
        var json = api.ajax.json();
        $.each(json.scheduleStatus, function(index, item) {
          console.log(item.name + item.schedules_count);
          $('#scheduleStatus option[value="'+ index +'"]').text(index + ' - ' + item.length);
        });
        $(".select2FormattedColor").select2({
                templateResult: formatStateColor,
                templateSelection: formatStateColor
          });
        $('[data-bs-toggle="tooltip"]').tooltip();
        feather.replace({
          width: 14,height: 14
        });
      };
      var order =  [[ 0, "desc" ]];

      $('.rangePick').flatpickr({
        mode: 'range',
        altFormat: 'Y-m-d',
        defaultDate: [new Date(), new Date().fp_incr(30)],
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        },
      });

      $(".select2FormattedColor").select2({
                templateResult: formatStateColor,
                templateSelection: formatStateColor
          });

            function formatStateColor (opt) {
                if (!opt.id) {
                    return opt.text.toUpperCase();
                }

                var color = $(opt.element).attr('data-color');
                if(!color){
                   return opt.text.toUpperCase();
                } else {
                    var $opt = $(
                       '<span class="px-1 text-white bg-' + color + '">' + opt.text.toUpperCase() + '</span>'
                    );
                    return $opt;
                }
            };
      @endif

  </script>
  @can('dashboard.scheduler')
  <script type="text/javascript">
    var dt_resource_schedule_table = $('#resource_schedules_table');
    if (dt_resource_schedule_table.length) {
    var dt_resource_schedule = dt_resource_schedule_table.DataTable({
      processing: true,
      serverSide: true,
      "scrollX": true,
      ajax: {
          url: "{{ route('dashboard.resources_schedules') }}",
          data: function (data) {
                data.resourceStatus = $("#resourceStatus").val();
                data.resourcesDateRange = $("#resourcesDateRange").val();
            }
        },
      order: [[ 0, "desc" ]],
      columns: [
            { data: 'id', name: 'id'},
            { data: 'schedule_link', name: 'schedule_link'},
            { data: 'name', name: 'modelable_id'},
            { data: 'status_formatted', name: 'status'},
            { data: 'start_date', name: 'start_date'},
            { data: 'end_date', name: 'end_date'},
            // { data: 'person_days', name: 'person_days'},
        ],
      dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"li><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu:  [7, 10, 25, 50, 75, 100],
      buttons: [],
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
      "drawCallback": function(){},
      "language": {"emptyTable": "No data available"},
    });
    // $('div.head-label').html('<h6 class="mb-0"> ' + table_title +' </h6>');
  }
  $(document).on('change', '.selectFilterResource', function() {
    dt_resource_schedule.ajax.reload();
  });
  </script>
  @endcan
  <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
@endsection


