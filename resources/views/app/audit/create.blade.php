@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create New Audit')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('audit.store') }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Client:</label>
                            <select class="form-control select2" name="client_company_id" id="client_company">
                              <option disabled selected></option>
                              @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->companyDetails }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_supplier">
                            <label for="name">Supplier:</label>
                            <select class="form-control select2" name="supplier_company_id" id="supplier_company">
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
                            <label for="name">Templates (One Time Fill-up):</label>
                            <select class="form-control select2" multiple name="singleTemplates[]" id="singleTemplates">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Templates (Multiple):</label>
                            <select class="form-control select2" multiple name="multipleTemplates[]" id="multipleTemplates">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>

                  </div>
                  <div class="row">
                    <div class="col-12 align-items-center justify-content-center text-center">
                      <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                    </div>
                  </div>
              </form>
        </div>
    </div>
</section>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
            $('.datePicker').flatpickr({
                altFormat: 'Y-m-d',
                defaultDate: new Date().fp_incr(30),
                onReady: function (selectedDates, dateStr, instance) {
                  if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                  }
                }
              });
            $(document).on('change', '#supplier_company', function(){
                var url = '{{ route("audit.loadSchedulesFor", ":id") }}';
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

              var url = '{{ route("audit.loadSchedulesFor", ":id") }}';
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
@endsection
