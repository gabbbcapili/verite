@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-xl">
    <form action="{{ route('schedule.update', $event->id) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('PUT')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Schedule
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
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
                        <option value="Audit Schedule" {{ $event->type == 'Audit Schedule' ? 'selected' : '' }}>Audit Schedule</option>
                        @endcan
                        <option value="Leave" {{ $event->type == 'Leave' ? 'selected' : '' }}>Leave</option>
                        <option value="Holiday" {{ $event->type == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="Unavailable" {{ $event->type == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                      </select>
                    </div>
                  </div>
                  <div id="rowLeave" class="d-none">
                    <div class="row mb-2 justify-content-md-center">
                      <div class="col-lg-6">
                        <label>Company</label>
                        <select name="company_id" class="form-control select2Modal">
                          @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $event->users()->first()->modelable_id == $company->id ? 'selected' : '' }}>{{ $company->displayName }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="rowSchedule" class="d-none">
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                          <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $schedule->title }}" readonly disabled>
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
                                <option value="{{ $audit_model->name }}" {{ $schedule->audit_model == $audit_model->name ? 'selected' : '' }}>{{ $audit_model->name }}</option>
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
                                  <option value="{{ $type }}" {{ $type == $schedule->audit_model_type ? 'selected' : '' }}>{{ $type }}</option>
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
                                <option value="{{ $status->name }}" {{ $schedule->status == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
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
                                <option value="{{ $country->id }}" {{ $schedule->country == $country->name ? 'selected' : '' }}>{{ $country->name }} - {{ $country->timezone }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <label>City:</label>
                        <input type="text" name="city" class="form-control" value="{{ $schedule->city }}">
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group p-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="1" name="with_completed_spaf" {{ $schedule->with_completed_spaf ? 'checked' : ''}} />
                            <label class="form-check-label">With Compelted SPAF?</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <label>Due Date:</label>
                        <input type="text" name="due_date" id="date_due_date" class="form-control">
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <label>Report Submitted:</label>
                        <input type="text" name="report_submitted" id="date_report_submitted" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_1 }}:</label>
                        <input type="text" name="cf_1" class="form-control" value="{{ $schedule->cf_1 }}">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_2 }}:</label>
                        <input type="text" name="cf_2" class="form-control" value="{{ $schedule->cf_2 }}">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_3 }}:</label>
                        <input type="text" name="cf_3" class="form-control" value="{{ $schedule->cf_3 }}">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_4 }}:</label>
                        <input type="text" name="cf_4" class="form-control" value="{{ $schedule->cf_4 }}">
                      </div>
                      <div class="col-lg-2 col-xs-12">
                          <label>{{ Helper::settings()->schedule_cf_5 }}:</label>
                        <input type="text" name="cf_5" class="form-control" value="{{ $schedule->cf_5 }}">
                      </div>
                    </div>
                    <div class="row mb-5">
                      <div class="row">
                        <div class="d-flex justify-content-end mb-1">
                          <button class="btn btn-primary" type="button" id="add_user"><i data-feather="plus-circle"></i> Add User</button>
                        </div>
                        <div class="table-responsive">
                          <input type="hidden" id="user_row_count" value="1">
                          <table class="table table-striped" id="user_table">
                            <thead>
                              <tr>
                                <th style="width: 40%;">User</th>
                                <th style="width: 40%;">Role</th>
                                <th style="width: 20%;">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center justify-content-center">
                      <div class="col-6" id="rowSpaf">

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        @if($event->created_by == $request->user()->id || $request->user()->can('schedule.manage'))
          <a href="#" data-action="{{ route('schedule.destroy', $event) }}" data-bs-toggle="tooltip" data-title="Are you sure to delete this schedule?" data-placement="top" title="Delete Schedule" class="btn btn-danger confirmDelete"><i data-feather="trash"></i> Delete Schedule</a>
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
          @endcan
      </div>
    </div>
  </form>
</div>
<script src="{{ asset(mix('js/scripts/forms-validation/form-modal.js')) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $('#view_modal').css('overflow-y', 'scroll');
      var date = '';
      var clientSelection = '';
      var userSelection = '';
      var roleTypes = @json(Helper::settings()->schedule_role_types());
      var roleTypesSelection = '';
      $('.select2Modal').select2({
        dropdownParent: $("#view_modal")
      });

      $('#date_due_date').flatpickr({
        altFormat: 'Y-m-d',
        defaultDate: "{{ $schedule->due_date ? $schedule->due_date : '' }}",
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        }
      });

      $('#date_report_submitted').flatpickr({
        altFormat: 'Y-m-d',
        defaultDate: "{{ $schedule->due_date ? $schedule->due_date : '' }}",
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        }
      });

      $('.rangePicker').flatpickr({
        mode: 'range',
        altFormat: 'Y-m-d',
        defaultDate: ["{{ $event->start_date }}", "{{ $event->end_date }}"],
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            $(instance.mobileInput).attr('step', null);
          }
        },
        onChange: function(selectedDates, dateStr, instance) {
            loadData(false);
        },
      });

      $(document).on('change', '#client_company', function(){
          loadAvailableSuppliers();
        });

      $(document).on('change', '#supplier_company', function(){
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

      function loadAvailableSuppliers(){
        var url = '{{ route("schedule.loadAvailableSuppliers", ":id") }}';
                      url = url.replace(':id', $('#client_company').val());
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
                  dropdownParent: $("#view_modal")
                });
              }
          });
      }

      $('#add_user').click(function(){
        addUser();
      });

      function addUser(){
        var row = parseInt($('#user_row_count').val()) + 1;
        $('#user_row_count').val(row);
        var $tr = '';

        $tr += '<tr>';
        $tr += '<td><select class="form-control select2Table userSelection" name="users['+ row +'][id]" id="users.'+ row +'.id">'+ userSelection +'</select></td>';
        $tr += '<td><select class="form-control select2Table" name="users['+ row +'][role]" id="users.'+ row +'.role">'+ roleTypesSelection +'</select></td>';
        $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button></div></div></td>';
        $tr += '</tr>';
        $('#user_table tr:last').after($tr);
        feather.replace({
          width: 14,height: 14
        });
        $('[data-bs-toggle-modal="tooltip"]').tooltip();
        $('.select2Table').select2({
          dropdownParent: $("#view_modal")
        })
      }

      function loadCurrentUser(){
        var currentSelection = '';
        @foreach($event->users->where('role', '!=', 'Client')->where('role', '!=', 'Supplier') as $user)

        @endforeach
        @foreach($event->users->where('role', '!=', 'Client')->where('role', '!=', 'Supplier') as $user)
        currentSelection = '<option value="{{ $user->modelable->id }}" selected>{{ $user->modelable->displayName }}</option>';
        var $tr = '';
          $tr += '<tr><input type="hidden" name="users[100{{ $loop->iteration }}][event_user_id]" value="{{ $user->id }}">';
          $tr += '<td><select class="form-control select2Table userSelection" name="users[100{{ $loop->iteration }}][id]" id="users.100{{ $loop->iteration }}.id">'+ userSelection + currentSelection +'</select></td>';
          $tr += '<td><select class="form-control select2Table" name="users[100{{ $loop->iteration }}][role]" id="users.100{{ $loop->iteration }}.role">'+ roleTypesSelection +'</select></td>';
          $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button></div></div></td>';
          $tr += '</tr>';
          $('#user_table tr:last').after($tr);
          $('#users.100{{ $loop->iteration }}.role').val("{{ $user->role }}");
        @endforeach
        $('.select2Table').select2({
          dropdownParent: $("#view_modal")
        });
        feather.replace({
          width: 14,height: 14
        });
      }

      $('#SelectType').change(function(){
        onChangeSelectType();
      });

      function onChangeSelectType(){
        if($('#SelectType').find(":selected").val() == 'Audit Schedule'){
          $('#rowSchedule').removeClass('d-none');
          loadData(false);
          @can('schedule.manage')
            $('#rowLeave').addClass('d-none');
          @endcan
        }else{
          $('#rowSchedule').addClass('d-none');
          @can('schedule.manage')
            $('#rowLeave').removeClass('d-none');
          @endcan
        }
      }

      $(document).on('click', '.delete_row', function(){
        $('[data-bs-toggle-modal="tooltip"]').tooltip('hide')
        $(this).closest('tr').remove();
      });

      function loadData(firstTime){
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
              },
              success:function(result)
              {
                clientSelection = '';
                userSelection = '';
                roleTypesSelection = '';
                $.each(result.data.users, function(k, u) {
                  userSelection += '<option value="'+ u.id +'">'+ u.displayName +'</option>'
                });
                $.each(result.data.clients, function(k, u) {
                  clientSelection += '<option value="'+ u.id +'">'+ u.company_name +'</option>'
                });
                $.each(roleTypes, function(k, u) {
                  roleTypesSelection += '<option value="'+ u +'">'+ u +'</option>'
                });
                $('#client_company').find('option').remove().end().append(clientSelection).val('');
                $('.userSelection').find('option').remove().end().append(userSelection).val('');
                $('#supplier_company').find('option').remove().end().val('');
                if(firstTime){
                  loadPreData();
                  loadCurrentUser();
                  loadSpaf();
                }
              }
          });
      }

      function loadPreData(){
        @if($event->type == 'Audit Schedule')
        @if($client)
        @if($client->blockable)
        $('#client_company').append('<option value="{{ $client->modelable_id }}" selected>{{ $client->modelable->company_name }}</option>').val('{{ $client->modelable_id }}');
        @else
          $('#client_company').val('{{ $client->modelable_id }}')
        @endif
          @if($supplier)
            $('#supplier_company').append('<option value="{{ $supplier->modelable_id }}" selected>{{ $supplier->modelable->company_name }}</option>').val('{{ $supplier->modelable_id }}');
            @else
            loadAvailableSuppliers();
          @endif
        @endif
      @endif
      }
      onChangeSelectType();//$('#SelectType').trigger('change');
      loadData(true);
      console.log(true);
    });

</script>
