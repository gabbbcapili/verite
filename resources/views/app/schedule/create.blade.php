@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-xl">
    <form action="{{ route('schedule.store') }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('POST')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add Schedule
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row" id="formRow">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="form-body">
                  <div class="row mb-2 justify-content-md-center">
                    <div class="col-6">
                      <label>Start & End Date</label>
                      <input type="text" name="start_end_date" class="form-control rangePicker" id="dateRange">
                    </div>
                  </div>
                  <div class="row mb-2 justify-content-md-center">
                    <div class="col-6">
                      <label>Type</label>
                      <select name="type" class="form-control select2Modal" id="SelectType">
                        <option disabled selected hidden></option>
                        @can('schedule.manage')
                        <option value="Audit Schedule">Audit Schedule</option>
                        @endcan
                        <option value="Leave">Leave</option>
                        <option value="Holiday">Holiday (Resource / Company)</option>
                        @can('schedule.manage')
                        <option value="Holiday Country">Holiday (Country / State)</option>
                        @endcan
                        <option value="Unavailable">Unavailable</option>
                      </select>
                    </div>
                  </div>
                  <div id="rowHolidayCountry" class="d-none">
                    <div class="row mb-2 justify-content-md-center">
                      <div class="col-lg-6 col-xs-12">
                        <label>Title:</label>
                        <input type="text" name="event_title" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-2 justify-content-md-center">
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="name">Country:</label>
                            <select class="form-control select2Modal" name="country_id" id="country-modal">
                              <option disabled selected></option>
                              @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2 justify-content-md-center">
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group" id="fg-state-modal">
                          <label for="state">State:</label>
                            <select class="form-control select2Modal" name="state_id" id="state">
                                <option disabled selected></option>
                            </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="rowLeave" class="d-none">
                    <div class="row mb-2 justify-content-md-center">
                      <div class="col-lg-6">
                        <label>Resource / Company</label>
                        <select name="unavailability_type" id="unavailabilityType" class="form-control select2Modal">
                          <option disabled selected>Select Option</option>
                          <option value="resource">Resource</option>
                          <option value="company">Company</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-2 justify-content-md-center d-none" id="rowLeaveCompany">
                      <div class="col-lg-6">
                        <label>Company</label>
                        <select name="company_id" class="form-control select2Modal">
                          <option disabled selected>Select Company</option>
                          @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->displayName }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row mb-2 justify-content-md-center d-none" id="rowLeaveResource">
                      <div class="col-lg-6">
                        <label>Resource</label>
                        <select name="user_id" class="form-control select2Modal">
                          <option disabled selected>Select Resource</option>
                          @foreach($auditors as $auditor)
                            <option value="{{ $auditor->id }}">{{ $auditor->displayName }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="rowSchedule" class="d-none">
                    <div class="row mb-2">
                      <divv class="col-lg-4">
                        <label>Copy From:</label>
                        <select class="form-control select2Modal" id="copyFrom">
                          <option selected disabled></option>
                          @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}">{{ $schedule->title }}</option>
                          @endforeach
                        </select>
                      </divv>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                          <label>Title</label>
                        <input type="text" name="title" class="form-control" readonly disabled>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Client:</label>
                            <select class="form-control select2Modal" name="client_company_id" id="client_company">
                              <option disabled selected></option>
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_supplier">
                            <label for="name">Supplier:</label>
                            <select class="form-control select2Modal" name="supplier_company_id" id="supplier_company">
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Audit Model:</label>
                            <select class="form-control select2Modal" name="audit_model">
                              <option disabled selected></option>
                              @foreach($auditmodels as $audit_model)
                                <option value="{{ $audit_model->name }}">{{ $audit_model->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Audit Model Type:</label>
                            <select class="form-control select2Modal" name="audit_model_type">
                              <option disabled selected></option>
                              @foreach(Helper::settings()->schedule_audit_model_types() as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Status:</label>
                            <select class="form-control select2Modal" name="status">
                              <option disabled selected></option>
                              @foreach($schedulestatuses as $status)
                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Country:</label>
                            <select class="form-control select2Modal" name="country">
                              <option disabled selected></option>
                              @foreach($countries as $country)
                                <option value="{{ $country->id }}" data-name="{{ $country->name }}">{{ $country->name }} - {{ $country->timezone }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <label>City:</label>
                        <input type="text" name="city" class="form-control">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                        <div class="form-group p-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="1" checked name="with_completed_spaf"/>
                            <label class="form-check-label">With Completed SPAF?</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 col-xs-12">
                        <div class="form-group p-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="1" checked name="with_quotation"/>
                            <label class="form-check-label">With Quotation?</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <label>Turn Around Days:</label>
                        <input type="text" name="turnaround_days" class="form-control">
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <label>Report Submitted:</label>
                        <input type="text" name="report_submitted" class="form-control datePicker">
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_1 }}:</label>
                        <input type="text" name="cf_1" class="form-control">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_2 }}:</label>
                        <input type="text" name="cf_2" class="form-control">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_3 }}:</label>
                        <input type="text" name="cf_3" class="form-control">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_4 }}:</label>
                        <input type="text" name="cf_4" class="form-control">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_5 }}:</label>
                        <input type="text" name="cf_5" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-5">
                      <!-- <div class="row"> -->
                        <div class="col-4"></div><div class="col-4"></div>
                        <div class="col-4 mb-1">
                          <label>Filter Resource</label>
                          <select class="form-control select2Modal" multiple id="filterProficiency">
                            @foreach($proficiencies as $proficiency)
                            <option value="{{ $proficiency->id }}">{{ $proficiency->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="d-flex justify-content-end mb-1">
                          <button class="btn btn-primary" type="button" id="add_user"><i data-feather="plus-circle"></i> Add Resource</button>
                        </div>
                        <span id="users"></span>
                        <div class="table-responsive" style="max-height:320px;" id="userTableResponsive">
                          <input type="hidden" id="user_row_count" value="0">
                          <table class="table table-striped" id="user_table" style="table-layout: fixed">
                            <thead>
                              <tr>
                                <th style="width: 15%;">Role</th>
                                <th style="width: 46%;">Resource</th>
                                <th style="width: 25%;">Start & End Date</th>
                                <th style="width: 7%;">Status</th>
                                <th style="width: 7%;">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      <!-- </div> -->
                    </div>
                    <div class="row mb-2">
                      <div class="col-6" id="rowSpaf">

                      </div>
                      <div class="col-6" id="rowResourcePlan">
                        <h4>Resource Plan</h4>
                        <div class="row mb-2">
                          <div class="col-6">
                            <label>Lead Auditor</label>
                            <input type="text" id="lead_auditor" class="form-control inputResourcePlan" data-selection="Lead Auditor" value="{{ Helper::settings()->lead_auditor }}">
                          </div>
                          <div class="col-6">
                            <label>Second Auditor</label>
                            <input type="text" id="second_auditor" class="form-control inputResourcePlan" data-selection="Second Auditor" value="{{ Helper::settings()->second_auditor }}">
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-6">
                            <label>Worker Interviewer</label>
                            <input type="text" id="worker_interviewer" class="form-control inputResourcePlan" data-selection="Worker Interviewer" value="{{ Helper::settings()->worker_interviewer }}">
                          </div>
                          <div class="col-6">
                            <label>EHS Auditor</label>
                            <input type="text" id="ehs_auditor" class="form-control inputResourcePlan" data-selection="EHS Auditor" value="{{ Helper::settings()->ehs_auditor }}">
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-6">
                            <label>ASR</label>
                            <input type="text" id="asr" class="form-control inputResourcePlan" data-selection="ASR" value="{{ Helper::settings()->asr }}">
                          </div>
                          <div class="col-6">
                            <label>Interpreter</label>
                            <input type="text" id="interpreter" class="form-control inputResourcePlan" data-selection="Interpreter" value="{{ Helper::settings()->interpreter }}">
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-6">
                            <label>Observer</label>
                            <input type="text" id="observer" class="form-control inputResourcePlan"data-selection="Observer" value="{{ Helper::settings()->observer }}">
                          </div>
                          <div class="col-6">
                            <button type="button" class="btn btn-primary mt-1" id="addResources">Add Resources</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="position: sticky; bottom: 0; z-index: 1000;">
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
      </div>
    </div>
  </form>
</div>
<script src="{{ asset(mix('js/scripts/forms-validation/form-modal.js')) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $('#formRow').css('overflow-y', 'scroll');
      var date = '';
      var clientSelection = '';
      var userSelection = '';
      var roleTypes = @json(Helper::settings()->schedule_role_types());
      var roleTypesSelection = '';
      $('.select2Modal').select2({
        dropdownParent: $("#formRow")
      });

      $('.datePicker').flatpickr({
        altFormat: 'Y-m-d',
        defaultDate: new Date(),
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        }
      });

      $('.rangePicker').flatpickr({
        mode: 'range',
        altFormat: 'Y-m-d',
        defaultDate: ["{{ $date ? $date : Carbon\Carbon::now()->format('Y-m-d') }}"],
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        },
        onClose: function(selectedDates, dateStr, instance) {
            var current_company = $('#client_company').find(":selected").val();
            var current_supplier = $('#supplier_company').find(":selected").val();
            var current_users = $('.userSelection').find(":selected");
            loadData();

            
            var allowedDates = scheduleGetAllowedDates();
            $('.tableRangePicker').flatpickr({
              mode: 'range',
              altFormat: 'Y-m-d',
              defaultDate: [allowedDates.startDate, allowedDates.endDate],
              enable: [{
                from: allowedDates.startDate,
                to: allowedDates.endDate,
              }],
            });
            setTimeout(function(){
              $('#client_company').val(current_company).trigger('change');
            }, 1000)
            setTimeout(function(){
              $('#supplier_company').val(current_supplier).trigger('change');
              $.each($('.userSelection'), function(index, item){
                var current_user = $(current_users[index]).val();
                $(item).val(current_user).trigger('change');
              });
            }, 2000)
        },
      });

      $(document).on('change', '#copyFrom', function(){
        var url = '{{ route("schedule.loadScheduleDetails", ":id") }}';
                      url = url.replace(':id', $(this).val());
        var copyFrom = $('#copyFrom').find(":selected").val();
        $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('.delete_row').click();
                $('#user_row_count').val(0);
                for (var i = 0; i < result.data.resource_count; i++){
                  addUser();
                }
                // client_company
                // supplier_company
                $('[name="audit_model"]').val(result.data.schedule.audit_model).trigger('change');
                $('[name="audit_model_type"]').val(result.data.schedule.audit_model_type).trigger('change');
                $('[name="status"]').val(result.data.schedule.status).trigger('change');
                $('[name="country"] option[data-name="' + result.data.schedule.country + '"]').prop("selected", true);
                $('[name="country"]').trigger('change');
                $('[name="city"]').val(result.data.schedule.city).trigger('change');
                $('[name="turnaround_days"]').val(result.data.schedule.turnaround_days);
                $('[name="cf_1"]').val(result.data.schedule.cf_1);
                $('[name="cf_2"]').val(result.data.schedule.cf_2);
                $('[name="cf_3"]').val(result.data.schedule.cf_3);
                $('[name="cf_4"]').val(result.data.schedule.cf_4);
                $('[name="cf_5"]').val(result.data.schedule.cf_5);
                var iteration = 1;
                $.each(result.data.resource, function(index, item){
                  if(item.modelable_type == 'App\\Models\\Company'){
                    if(item.role == 'Client'){
                      $('#client_company').val(item.modelable_id).trigger('change');
                    }else if (item.role == 'Supplier'){
                      setTimeout(function(){
                        $('#supplier_company').val(item.modelable_id).trigger('change');
                      }, 2000)
                    }
                  }else{
                    $('[name="users['+ iteration +'][id]"]').val(item.modelable_id).trigger('change');
                    iteration += 1;
                  }
                });
              }
          });
      });

      $(document).on('change', '#client_company', function(){
        if($(this).val() == null){
          return 0;
        }
          var url = '{{ route("schedule.loadAvailableSuppliers", ":id") }}';
                      url = url.replace(':id', $(this).val());
          $.ajax({
              url: url,
              method: "POST",
              data:{
                date : date,
              },
              success:function(result)
              {
                $('#fg_supplier').html(result);
                $('#supplier_company').select2({
                  dropdownParent: $("#formRow")
                });
                loadSpaf();
              }
          });
        });
      $(document).on('change', '#supplier_company', function(){
        if($(this).val() == null){
          return 0;
        }
        loadSpaf();
      });

      function loadSpaf(){
        var url = '{{ route("schedule.loadSpaf", ":id") }}';
        var client = $('#client_company').val();
        var supplier = $('#supplier_company').val();
        if(supplier != null){
          url = url.replace(':id', supplier);
        }else{
          url = url.replace(':id', client);
        }
        $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('#rowSpaf').html(result);
              }
          });
      }
      function addUser(){
        var row = parseInt($('#user_row_count').val()) + 1;
        $('#user_row_count').val(row);
        var $tr = '';
        
        
        $tr += '<tr>';
        $tr += '<td><select class="form-control select2Table roleSelection" name="users['+ row +'][role]" id="users.'+ row +'.role">'+ roleTypesSelection +'</select></td>';
        $tr += '<td><select class="form-control select2Table userSelection" name="users['+ row +'][id]" id="users.'+ row +'.id">'+ userSelection +'</select></td>';
        $tr += '<td><input type="text" class="form-control tableRangePicker" name="users['+ row +'][start_end_date]" id="users.'+ row +'.start_end_date"></td>';
        $tr += '<td>Pending</td>';
        $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button></div></div></td>';
        $tr += '</tr>';
        $('#user_table tr:last').after($tr);
        var allowedDates = scheduleGetAllowedDates();
        $('input[id="users.'+ row +'.start_end_date"]').flatpickr({
          mode: 'range',
          altFormat: 'Y-m-d',
          defaultDate: [allowedDates.startDate, allowedDates.endDate],
          enable: [{
            from: allowedDates.startDate,
            to: allowedDates.endDate,
          }],
        });
        feather.replace({
          width: 14,height: 14
        });
        $('[data-bs-toggle-modal="tooltip"]').tooltip();
        $('.select2Table').select2({
          dropdownParent: $("#userTableResponsive")
        })
      }
      $('#add_user').click(function(){
        addUser();
      });

      $('#SelectType').change(function(){
        if($(this).find(":selected").val() == 'Audit Schedule'){
          $('#rowSchedule').removeClass('d-none');
          $('#rowHolidayCountry').addClass('d-none');
          loadData();
          @can('schedule.manage')
            $('#rowLeave').addClass('d-none');
          @endcan
        }else if($(this).find(":selected").val() == 'Holiday Country'){
          $('#rowHolidayCountry').removeClass('d-none');
          $('#rowSchedule').addClass('d-none');
          $('#rowLeave').addClass('d-none');
        }
        else{
          $('#rowSchedule').addClass('d-none');
          $('#rowHolidayCountry').addClass('d-none');
          @can('schedule.manage')
          $('#rowLeave').removeClass('d-none');
          @endcan
        }
      })

      @can('schedule.manage')

      $(document).on('change', '#country-modal', function(){
        var url = '{{ route("country.loadStates", ":id") }}';
                    url = url.replace(':id', $(this).val());
        $.ajax({
            url: url,
            method: "POST",
            success:function(result)
            {
              $('#fg-state-modal').html(result);
              $('#view_modal').find('#state').select2({
                dropdownParent: $("#view_modal")
              });
            }
        });
      });

      $('#unavailabilityType').change(function(){
        if($(this).find(":selected").val() == 'resource'){
          $('#rowLeaveResource').removeClass('d-none');
          $('#rowLeaveCompany').addClass('d-none');
        }else{
          $('#rowLeaveResource').addClass('d-none');
          $('#rowLeaveCompany').removeClass('d-none');
        }
      });
      @endcan

      $(document).on('click', '.delete_row', function(){
        $('[data-bs-toggle-modal="tooltip"]').tooltip('hide')
        $(this).closest('tr').remove();
      });

      $(document).on('change', '#filterProficiency', function(){
        loadData(false);
      });

      function loadData(withClientsSuppliers = true){
        date = $('#dateRange').val();
          if(date == ""){
            $('#SelectType').val('').trigger('change');
            alert('Please select a Date first');
            return;
          }
          $.ajax({
              url: "{{ route('schedule.loadAvailableUsers') }}",
              method: "POST",
              data:{
                date : date,
                proficiencies : $('#filterProficiency').val(),
              },
              success:function(result)
              {
                clientSelection = '';
                userSelection = '';
                roleTypesSelection = '';
                $.each(result.data.users, function(k, u) {
                  userSelection += '<option value="'+ u.id +'">'+ u.displayName +'</option>'
                });
                $.each(roleTypes, function(k, u) {
                  roleTypesSelection += '<option value="'+ u +'">'+ u +'</option>'
                });
                $('.userSelection').find('option').remove().end().append(userSelection).val('');
                if(withClientsSuppliers){
                  $.each(result.data.clients, function(k, u) {
                    clientSelection += '<option value="'+ u.id +'">'+ u.company_name +'</option>'
                  });
                  $('#client_company').find('option').remove().end().append(clientSelection).val('');
                  $('#supplier_company').find('option').remove().end().val('');
                  $('#rowSpaf').html('');
                }
              }
          });
      }
      $('#addResources').click(function(){
         $(".inputResourcePlan").each(function(){
            var value = $(this).val();
            var id = $(this).attr('id');
            for(let i = 0; i < value; i++){
              $('#add_user').click();
              $('#user_table tr:last .roleSelection').val($(this).data('selection')).trigger('change');
            }
          });
      });
    });
</script>
