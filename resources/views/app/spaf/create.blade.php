@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create New Assessment')

@section('vendor-style')

@endsection

@section('content')
<section id="card-actions">
  <form action="{{ route('spaf.store') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Create Assessment</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
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
                        <div class="form-group" id="fg_client_cp">
                            <label for="name">Contact Person:</label>
                            <select class="form-control select2" name="client_id" id="client">
                            </select>
                        </div>
                      </div>

                    </div>
                    <hr>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_supplier">
                            <label for="name">Supplier:</label>
                            <select class="form-control select2" name="supplier_company_id" id="supplier_company">
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group" id="fg_supplier_cp">
                            <label for="name">Contact Person:</label>
                            <select class="form-control select2" name="supplier_id" id="supplier">
                            </select>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Template:</label>
                            <select class="form-control select2" name="template_id">
                              <option disabled selected></option>
                              @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->typeDisplay }} - {{ $template->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                          <div class="form-group">
                            <label>Send Email to Client/Supplier?</label>
                            <div class="row mt-1">
                              <div class="col-12">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="sendEmail" id="inlineRadio1" value="1" checked />
                                  <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="sendEmail" id="inlineRadio2" value="0" />
                                  <label class="form-check-label" for="inlineRadio2">No</label>
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
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-12 align-items-center justify-content-center text-center">
                          <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                          <button type="reset" class="btn btn-outline-warning mr-1">Clear </button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
</section>
@endsection

@section('vendor-script')
  {{-- vendor files --}}

@endsection
@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
      $(document).ready(function(){
        $('.select2').select2();
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

          var url = '{{ route("spaf.loadClientContactPersons", ":id") }}';
                      url = url.replace(':id', $(this).val());
          $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('#fg_client_cp').html(result);
                $('.select2').select2();
              }
          });
        });

        $(document).on('change', '#supplier_company', function(){
          var url = '{{ route("spaf.loadSupplierContactPersons", ":id") }}';
                      url = url.replace(':id', $(this).val());
          $.ajax({
              url: url,
              method: "POST",
              success:function(result)
              {
                $('#fg_supplier_cp').html(result);
                $('.select2').select2();
              }
          });
        });



      });
  </script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection


