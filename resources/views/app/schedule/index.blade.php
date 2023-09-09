@extends('layouts/contentLayoutMaster')

@section('title', 'Calendar')

@section('vendor-style')
  <!-- Vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar/main.min.css"> -->


  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<!-- Full calendar start -->
<section>
  <div class="app-calendar overflow-hidden border">
    <div class="row g-0">
      <!-- Sidebar -->
      <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
        <div class="sidebar-wrapper">
          <div class="p-1 d-flex justify-content-center">
            <button class="btn btn-primary btn-toggle-sidebar w-100 modal_button" id="addEventButton" data-action="{{ route('schedule.create') }}">
              <span class="align-middle">Add Schedule</span>
            </button>

          </div>
          <div class="px-1 d-flex justify-content-center">
            <a class="btn btn-primary btn-toggle-sidebar w-100" target="_blank" href="{{ route('schedule.ganttChart') }}">
              <span class="align-middle">View Gantt Chart</span>
            </a>

          </div>
          @can('schedule.manage')
          <div class="card-body pb-0">
            <h5 class="section-label mb-1">
              <span class="align-middle">Filter</span>
            </h5>
            <div class="form-check mb-1 d-none">
              <input type="checkbox" class="form-check-input select-all" id="select-all" checked />
              <label class="form-check-label" for="select-all">View All</label>
            </div>
            <div class="calendar-events-filter d-none">
              <div class="form-check form-check-danger mb-1">
                <input
                  type="checkbox"
                  class="form-check-input input-filter"
                  id="personal"
                  data-value="personal"
                  checked
                />
                <label class="form-check-label" for="personal">Personal</label>
              </div>
              <div class="form-check form-check-primary mb-1">
                <input
                  type="checkbox"
                  class="form-check-input input-filter"
                  id="business"
                  data-value="business"
                  checked
                />
                <label class="form-check-label" for="business">Business</label>
              </div>
              <div class="form-check form-check-warning mb-1">
                <input type="checkbox" class="form-check-input input-filter" id="family" data-value="family" checked />
                <label class="form-check-label" for="family">Family</label>
              </div>
              <div class="form-check form-check-success mb-1">
                <input
                  type="checkbox"
                  class="form-check-input input-filter"
                  id="holiday"
                  data-value="holiday"
                  checked
                />
                <label class="form-check-label" for="holiday">Holiday</label>
              </div>
              <div class="form-check form-check-info">
                <input type="checkbox" class="form-check-input input-filter" id="etc" data-value="etc" checked />
                <label class="form-check-label" for="etc">ETC</label>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12">
                <div class="form-group">
                  <select class="form-control select2 eventFilter" id="companyFilter">
                    <option value="all" selected>Show All Client / Supplier</option>
                    <option value="null">Hide All Leave, Holiday, Unavailable</option>
                    @foreach($companies as $company)
                      <option value="{{ $company->id }}">{{ $company->displayName }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-12">
                <div class="form-group">
                  <select class="form-control select2 eventFilter" id="auditorFilter">
                    <option value="all" selected>Show All Resources</option>
                    <option value="null">Hide All Leave, Holiday, Unavailable</option>
                    @foreach($auditors as $auditor)
                      <option value="{{ $auditor->id }}">{{ $auditor->fullName }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="card-body d-flex justify-content-center">
            <button class="btn btn-warning btn-toggle-sidebar w-100" id="resetValuesButton">
              <span class="align-middle">Reset Filters</span>
            </button>
          </div>
          </div>
          @endcan
        </div>
        <div class="mt-auto">
          <img
            src="{{asset('images/pages/calendar-illustration.png')}}"
            alt="Calendar illustration"
            class="img-fluid"
          />
        </div>
      </div>
      <!-- /Sidebar -->

      <!-- Calendar -->
      <div class="col position-relative">
        <div class="card shadow-none border-0 mb-0 rounded-0">
          <div class="card-body pb-0">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
      <!-- /Calendar -->
      <div class="body-content-overlay"></div>
    </div>
  </div>
</section>
<!-- Full calendar end -->
@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset(mix('vendors/js/calendar/fullcalendar.min.js')) }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar/main.min.js"></script> -->

  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
  <!-- Page js files -->
  <script type="text/javascript">

    $('.select2').select2();
    var route = "{{ route('schedule.create') }}";
    'use-strict';



    document.addEventListener('DOMContentLoaded', function () {
      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
      var calendarEl = document.getElementById('calendar'),
        eventToUpdate,
        sidebar = $('.event-sidebar'),
        calendarsColor = {
          primary: 'primary',
          secondary: 'secondary',
          success: 'success',
          warning: 'warning',
          info: 'info',
          danger: 'danger'
        },
        eventForm = $('.event-form');
        // selectAll = $('.select-all'),
        // calEventFilter = $('.calendar-events-filter'),
        // filterInput = $('.input-filter');

      // Selected Checkboxes
      // function selectedCalendars() {
      //   var selected = [];
      //   $('.calendar-events-filter input:checked').each(function () {
      //     selected.push($(this).attr('data-value'));
      //   });
      //   return selected;
      // }

      // --------------------------------------------------------------------------------------------------
      // AXIOS: fetchEvents
      // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
      // --------------------------------------------------------------------------------------------------


      function renderCalendar(company,auditor){
        var calendar = new FullCalendar.Calendar(calendarEl, {
          eventStartEditable: false,
          displayEventTime: false,
          initialView: 'dayGridMonth',
          events: {
              type: 'POST',
              url: "{{ route('schedule.getEvents') }}",
              extraParams: function(){
                return {
                  company: company,
                  auditor: auditor,
                }
              },
              error: function () {
                  alert('there was an error while fetching events!');
              },
          },

          eventOverlap: false,

          dayMaxEvents: 3,
          customButtons: {
            sidebarToggle: {
              text: 'Sidebar'
            }
          },

          headerToolbar: {
            start: 'sidebarToggle, prev,next, title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          direction: 'ltr',
          initialDate: new Date(),
          navLinks: true, // can click day/week names to navigate views
          eventClassNames: function ({ event: calendarEvent }) {
            const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
            return [
              // Background Color
              'bg-light-' + colorName,
              // 'modal_button',
              'hrefButton'
            ];
          },
          dateClick: function (info) {
            $.ajax({
              url: "{{ route('schedule.create') }}",
              data: {
                'date' :info.dateStr,
              },
              method: "GET",
              success:function(result)
              {
                $('#view_modal').html(result);
                  if($('#view_modal').is(':visible')){
                  }else{
                    $('#view_modal').modal({backdrop: 'static', keyboard: false}).modal('toggle');
                  }
                  if (feather) {
                    feather.replace({
                      width: 14, height: 14
                    });
                  }
              }
          });
          },
          eventDidMount: function(data) {
            // var url ="{{ route('schedule.edit', ':id') }}";
            var url ="{{ route('schedule.editNew', ':id') }}";
            url = url.replace(':id', data.event._def.publicId);
              data.el.setAttribute("data-action", url);
          },
        });
        // Render calendar
        calendar.render();
      }

      renderCalendar("all", "all");

      $(document).on('change', '.eventFilter', function(){
        var company = $('#companyFilter').find(":selected").val();
        var auditor = $('#auditorFilter').find(":selected").val();
        renderCalendar(company,auditor);
      });

      $('#resetValuesButton').click(function(){
        $("#companyFilter").val("null").trigger('change');
        $("#auditorFilter").val("null").trigger('change');
      });


      $(document).on('hidden.bs.modal', '#view_modal', function () {
        var company = $('#companyFilter').find(":selected").val();
        var auditor = $('#auditorFilter').find(":selected").val();
        renderCalendar(company,auditor);
      });

      // Select all & filter functionality
      // if (selectAll.length) {
      //   selectAll.on('change', function () {
      //     var $this = $(this);

      //     if ($this.prop('checked')) {
      //       calEventFilter.find('input').prop('checked', true);
      //     } else {
      //       calEventFilter.find('input').prop('checked', false);
      //     }
      //     calendar.refetchEvents();
      //   });
      // }

      // if (filterInput.length) {
      //   filterInput.on('change', function () {
      //     $('.input-filter:checked').length < calEventFilter.find('input').length
      //       ? selectAll.prop('checked', false)
      //       : selectAll.prop('checked', true);
      //     calendar.refetchEvents();
      //   });
      // }
    });
  </script>
  <script src="{{ asset(mix('js/scripts/pages/app-calendar-events.js')) }}"></script>
@endsection
