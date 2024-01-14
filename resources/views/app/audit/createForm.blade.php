@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Add Forms to Audit')

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
            <form action="{{ route('audit.storeForm', ['audit' => $audit]) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
              <div class="form-body">
                  <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Templates (One Time Fill-up):</label>
                            <select class="form-control select2" multiple name="singleTemplates[]" id="singleTemplates">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ in_array($template->id, $auditFormsSingle) ? 'disabled' : '' }}>{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Templates (Multiple):</label>
                            <select class="form-control select2" multiple name="multipleTemplates[]" id="multipleTemplates">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ in_array($template->id, $auditFormsMultiple) ? 'disabled="disabled"' : '' }}>{{ $template->name }}</option>
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
          });
    </script>
@endsection
