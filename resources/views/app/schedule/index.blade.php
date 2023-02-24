@extends('layouts/contentLayoutMaster')

@section('title', 'App Calender')

@section('vendor-style')
  <!-- Vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
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
          <div class="card-body d-flex justify-content-center">
            <button class="btn btn-primary btn-toggle-sidebar w-100 modal_button" id="addEventButton" data-action="{{ route('schedule.create') }}">
              <span class="align-middle">Add Schedule</span>
            </button>
          </div>
          <div class="card-body pb-0">
            <h5 class="section-label mb-1">
              <span class="align-middle">Filter</span>
            </h5>
            <div class="form-check mb-1">
              <input type="checkbox" class="form-check-input select-all" id="select-all" checked />
              <label class="form-check-label" for="select-all">View All</label>
            </div>
            <div class="calendar-events-filter">
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
          </div>
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
  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
  <!-- Page js files -->
  <script type="text/javascript">
    var route = "{{ route('schedule.create') }}";
    'use-strict';

    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('calendar'),
        eventToUpdate,
        sidebar = $('.event-sidebar'),
        calendarsColor = {
          Business: 'primary',
          Holiday: 'success',
          Personal: 'danger',
          Family: 'warning',
          ETC: 'info'
        },
        eventForm = $('.event-form'),
        addEventBtn = $('.add-event-btn'),
        cancelBtn = $('.btn-cancel'),
        updateEventBtn = $('.update-event-btn'),
        selectAll = $('.select-all'),
        calEventFilter = $('.calendar-events-filter'),
        filterInput = $('.input-filter');

      // --------------------------------------------
      // On add new item, clear sidebar-right field fields
      // --------------------------------------------
      $('.add-event button').on('click', function (e) {
        $('.event-sidebar').addClass('show');
        $('.sidebar-left').removeClass('show');
        $('.app-calendar .body-content-overlay').addClass('show');
      });


      // Event click function
      function eventClick(info) {

      }


      // Selected Checkboxes
      function selectedCalendars() {
        var selected = [];
        $('.calendar-events-filter input:checked').each(function () {
          selected.push($(this).attr('data-value'));
        });
        return selected;
      }

      // --------------------------------------------------------------------------------------------------
      // AXIOS: fetchEvents
      // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
      // --------------------------------------------------------------------------------------------------


      // Calendar plugins
      var calendar = new FullCalendar.Calendar(calendarEl, {
        eventStartEditable: false,
        displayEventTime: false,
        initialView: 'dayGridMonth',
        events : "{{ route('schedule.getEvents') }}",
        // events: function(start,end,successCallback){
        //   $.ajax(
        //   {
        //     url: "{{ route('schedule.getEvents') }}",
        //     type: 'GET',
        //     dataType: 'JSON',
        //     success: function (result) {
        //       console.log('test');
        //       // Get requested calendars as Array
        //       var calendars = selectedCalendars();
        //       var events = [];
        //       $.map( result.events, function( r ) {
        //           events.push({
        //               title: r.title,
        //               // start: new Date(r.start),
        //               // end: new Date(r.end)
        //               start : r.start,
        //               end : r.end
        //           });
        //       });
        //       console.log(events);
        //       successCallback(events);
        //       return [result.events.filter(event => calendars.includes(event.extendedProps.calendar))];
        //     },
        //     error: function (error) {
        //       console.log(error);
        //     }
        //   }
        // );
        // },
        editable: true,
        dragScroll: true,
        dayMaxEvents: 2,
        eventResizableFromStart: true,
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
            'modal_button'
          ];
        },
        dateClick: function (info) {
          // $('#addEventButton').click();
        },
        eventClick: function (info) {
          eventClick(info);
        },
        datesSet: function () {
        },
        viewDidMount: function () {
        },
        eventDidMount: function(data) {
          var url ="{{ route('schedule.edit', ':id') }}";
          url = url.replace(':id', data.event._def.publicId);
            data.el.setAttribute("data-action", url);
        },
        select: function( start, end, allDay, jsEvent, view ) {
          console.log(start);
        },
      });



      // Render calendar
      calendar.render();
      // updateEventClass();


      $(document).on('hidden.bs.modal', '#view_modal', function () {
        calendar.refetchEvents();
      });
      // ------------------------------------------------
      // addEvent
      // ------------------------------------------------
      function addEvent(eventData) {
        calendar.refetchEvents();
      }

      // ------------------------------------------------
      // updateEvent
      // ------------------------------------------------
      function updateEvent(eventData) {
        calendar.refetchEvents();
      }

      // ------------------------------------------------
      // removeEvent
      // ------------------------------------------------
      function removeEvent(eventId) {
        removeEventInCalendar(eventId);
      }

      // Select all & filter functionality
      if (selectAll.length) {
        selectAll.on('change', function () {
          var $this = $(this);

          if ($this.prop('checked')) {
            calEventFilter.find('input').prop('checked', true);
          } else {
            calEventFilter.find('input').prop('checked', false);
          }
          calendar.refetchEvents();
        });
      }

      if (filterInput.length) {
        filterInput.on('change', function () {
          $('.input-filter:checked').length < calEventFilter.find('input').length
            ? selectAll.prop('checked', false)
            : selectAll.prop('checked', true);
          calendar.refetchEvents();
        });
      }
    });


// function fetchEvents(start, end, successCallback) {
      //   // Fetch Events from API endpoint reference
      //   $.ajax(
      //     {
      //       url: "{{ route('schedule.getEvents') }}",
      //       type: 'GET',
      //       success: function (result) {
      //         // Get requested calendars as Array
      //         var calendars = selectedCalendars();
      //         var events = [];
      //         $.map( result.events, function( r ) {
      //             events.push({
      //                 title: r.title,
      //                 start: r.start,
      //                 end: r.start
      //             });
      //         });
      //         successCallback(events);
      //         // return [result.events.filter(event => calendars.includes(event.extendedProps.calendar))];
      //       },
      //       error: function (error) {
      //         console.log(error);
      //       }
      //     }
      //   );

      //   // var calendars = selectedCalendars();
      //   // // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
      //   // // You should make an API call, look into above commented API call for reference
      //   // selectedEvents = events.filter(function (event) {
      //   //   // console.log(event.extendedProps.calendar.toLowerCase());
      //   //   return calendars.includes(event.extendedProps.calendar.toLowerCase());
      //   // });
      //   // // if (selectedEvents.length > 0) {
      //   // successCallback(selectedEvents);
      //   // // }
      // }

  </script>
  <script src="{{ asset(mix('js/scripts/pages/app-calendar-events.js')) }}"></script>
@endsection
