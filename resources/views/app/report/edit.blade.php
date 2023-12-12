@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', $report->title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
<!-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.colors.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.emoji.min.css')) }}"> -->

@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <style type="text/css">
    .forInsertion{
      cursor: pointer !important;
      border: 1px solid #7367f0 !important;
    }
  </style>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('report.update', $report) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row mb-2">
                    <div class="col-lg-4">
                        <a target="_blank" href="{{ route('audit.show', $report->audit->id) }}" class="btn btn-success"><i data-feather="eye"></i> View Audit</a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-7">
                        <textarea class="form-control tinymce" name="content" id="content">{!! $report->content !!}</textarea>
                    </div>
                    <div class="col-lg-5">
                      @include('app.template.group.forInsertionModal')
                    </div>
                </div>
                @if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier'))
                @else
                  <div class="row">
                    <div class="col-12 align-items-center justify-content-center text-center">
                      <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                    </div>
                  </div>
                @endif
              </form>
        </div>
    </div>
<!-- Modal -->

</section>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<!-- <script src="{{ asset(mix('vendors/js/trumbowyg/trumbowyg.min.js')) }}"></script>
<script src="{{ asset('vendors/js/trumbowyg/trumbowyg.colors.min.js') }}"></script>
<script src="{{ asset('vendors/js/trumbowyg/trumbowyg.emoji.min.js') }}"></script>
<script src="{{ asset('vendors/js/trumbowyg/trumbowyg.fontsize.min.js') }}"></script> -->
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            tinymce.init({
                  selector: ".tinymce",
                  plugins: 'pagebreak image code fullscreen table lists',
                  height : "1000"
                });
            $('.forInsertion').dblclick(function(){
                var self = $(this);
                var text = '';
                if (self.is("input")) {
                    text = self.val();
                } else if (self.is("radio")) {
                     // text = self.val();
                } else if (self.is("textarea")) {
                    text = self.val();
                }else if (self.is("td")) {
                    text = self.html();
                }else if (self.is("thead")) {
                    text = self.data('forinsertionreport');
                }else if (self.is("table")) {
                    text = self.html();
                }else if (self.is("div")) {
                    text = self.html();
                }
                tinymce.activeEditor.execCommand('mceInsertContent', false, text);
                // $('#selectVariableModal').modal('hide');
            });
            $('#selectVariableModalButton').click(function(){
                // $('#selectVariableModal').modal('toggle');
            });

            $('input,textarea').attr('readonly', 'readonly');
            $('input:not([checked])').attr('disabled', true);


          });
    </script>
@endsection
