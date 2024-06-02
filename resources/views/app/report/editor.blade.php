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
    .sticky-column {
      background-color: #f8f9fa;
      height: 100%;
      padding: 15px;
      border: 1px solid #dee2e6;
    }
  </style>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">

            <div class="d-flex bd-highlight mb-1">
              <div class="bd-highlight"><a target="_blank" href="{{ route('audit.show', $report->audit->id) }}" class="btn btn-success me-1"><i data-feather="eye"></i> View Audit</a></div>
              <div class="ms-auto bd-highlight w-25">
                    <select class="form-control select2-multiple" id="filterStandards" multiple>
                        @foreach($standards as $standard)
                            <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 sticky-top sticky-column">
                    <form action="{{ route('report.editorUpdate', $report) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <textarea class="form-control tinymce" name="content" id="content">{!! $report->content !!}</textarea>
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
                <div class="col-lg-5" id="forInsertion">
                  @include('app.template.group.forInsertionModal')
                </div>
            </div>
            
 
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
                }else if (self.hasClass("forInsertionWithTarget")) {

                    text = $('#' + self.data('target')).html();
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
            $('#forInsertion input:not([checkstate="true"])').attr('disabled', true);

            $('.select2-multiple').select2({
                placeholder: "Select Standards to Filter",
            });

            $('#filterStandards').change(function(){
                
                // Get selected values
                var selectedStandards = $(this).val();
                
                // Hide all rows initially
                $('.rowStandard').addClass('d-none');
                
                // If no standards are selected, show all rows
                if(selectedStandards.length === 0) {
                    $('.rowStandard').removeClass('d-none');
                } else {
                    // Show rows corresponding to selected standards
                    selectedStandards.forEach(function(standardId) {
                        $('#row-standard-' + standardId).removeClass('d-none');
                    });
                }
            });
          });
    </script>
@endsection
