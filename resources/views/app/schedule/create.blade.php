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
                        <option value="Audit Schedule">Audit Schedule</option>
                        @endcan
                        <option value="Leave">Leave</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Unavailable">Unavailable</option>
                      </select>
                    </div>
                  </div>
                  <div id="rowLeave" class="d-none">
                    <div class="row mb-2 justify-content-md-center">
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
                  </div>
                  <div id="rowSchedule" class="d-none">
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                          <label>Title</label>
                        <input type="text" name="title" class="form-control">
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
                                <option value="{{ $country->id }}">{{ $country->name }} - {{ $country->timezone }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <label>City:</label>
                        <input type="text" name="city" class="form-control">
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group p-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="1" checked name="with_completed_spaf"/>
                            <label class="form-check-label">With Compelted SPAF?</label>
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
                    <div class="row mb-2">
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
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
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
        onChange: function(selectedDates, dateStr, instance) {
            loadData();
        },
      });

      $(document).on('change', '#client_company', function(){
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
                  dropdownParent: $("#view_modal")
                });
              }
          });
        });

      $('#add_user').click(function(){
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
      });

      $('#SelectType').change(function(){
        if($(this).find(":selected").val() == 'Audit Schedule'){
          $('#rowSchedule').removeClass('d-none');
          loadData();
          @can('schedule.manage')
            $('#rowLeave').addClass('d-none');
          @endcan
        }else{

          $('#rowSchedule').addClass('d-none');
          @can('schedule.manage')
          $('#rowLeave').removeClass('d-none');
          @endcan
        }
      })

      $(document).on('click', '.delete_row', function(){
        $('[data-bs-toggle-modal="tooltip"]').tooltip('hide')
        $(this).closest('tr').remove();
      });

      function loadData(){
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

              }
          });
      }
    });
</script>
