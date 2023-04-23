@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('schedule.auditProgram.update', ['auditProgram' => $auditProgram]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Audit Program
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="form-body">
                  <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Client:</label>
                            <select class="form-control select2" name="client_company_id" id="client_company">
                              <option disabled selected></option>
                              @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $selectedClient->modelable_id == $client->id ? 'selected' : '' }}>{{ $client->companyDetails }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_supplier">
                            <label for="name">Supplier:</label>
                            <select class="form-control select2" name="supplier_company_id" id="supplier_company">
                              @if($selectedSupplier)
                                @foreach($selectedClient->modelable->suppliers as $supplier)
                                  <option value="{{ $supplier->id }}" {{ $selectedSupplier->modelable_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->companyDetails }}</option>
                                @endforeach
                              @else
                                <option disabled selected></option>
                                @foreach($selectedClient->modelable->suppliers as $supplier)
                                  <option value="{{ $supplier->id }}">{{ $supplier->companyDetails }}</option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_schedule">
                            <label for="name">Schedule:</label>
                            <select class="form-control select2" name="schedule_id" id="schedule">
                            </select>
                        </div>
                      </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Start Date:</label>
                            <input type="text" class="form-control datePicker" name="start_date">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Frequency (Months):</label>
                            <input type="text" class="form-control" name="frequency" value="{{ $auditProgram->frequency }}">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Length of Program (Months):</label>
                            <input type="text" class="form-control" name="length" value="{{ $auditProgram->length }}">
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
        @if($selectedSupplier)
          var url = '{{ route("schedule.loadSchedulesFor", ":id") }}';
                      url = url.replace(':id', $('#supplier_company').val());
        @else
          var url = '{{ route("schedule.loadSchedulesFor", ":id") }}';
                      url = url.replace(':id', $('#client_company').val());
        @endif
        loadSchedules(url);
        setTimeout(function(){
          $('#schedule').val('{{ $auditProgram->schedule_id }}').trigger('change');
        }, 500)
        $('.select2').select2();
        $('.datePicker').flatpickr({
            altFormat: 'Y-m-d',
            defaultDate: '{{ $auditProgram->start_date }}',
            onReady: function (selectedDates, dateStr, instance) {
              if (instance.isMobile) {
                $(instance.mobileInput).attr('step', null);
              }
            }
          });
        $(document).on('change', '#supplier_company', function(){
            var url = '{{ route("schedule.loadSchedulesFor", ":id") }}';
                      url = url.replace(':id', $(this).val());
            loadSchedules(url);
        });
        $(document).on('change', '#client_company', function(){
          var url = '{{ route("spaf.loadSuppliers", ":id") }}';
                      url = url.replace(':id', $(this).val());
          $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('#fg_supplier').html(result);
                $('.select2').select2();
              }
          });

          var url = '{{ route("schedule.loadSchedulesFor", ":id") }}';
                      url = url.replace(':id', $(this).val());
          loadSchedules(url);

        });

        function loadSchedules(url){
            $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('#fg_schedule').html(result);
                $('.select2').select2();
              }
          });
        }
      });
</script>
