@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create New Report')

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
            <form action="{{ route('report.store') }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="name">Audit:</label>
                            <select class="form-control select2" name="audit_id" id="audit_id">
                              <option disabled selected></option>
                              @foreach($audits as $audit)
                                <option value="{{ $audit->id }}">{{ $audit->schedule->title }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="name">Template:</label>
                            <select class="form-control select2" name="template_id" id="template_id">
                              <option disabled selected></option>
                              @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label>Report Title:</label>
                            <input type="text" name="title" class="form-control">
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
          });
    </script>
@endsection
