@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Edit '. $template->name .' Template')

@section('vendor-style')
<style type="text/css">
    .ui-sortable-helper {
        display: table;
    }
    .forInsertion{
      cursor: pointer !important;
      border: 1px solid #7367f0 !important;
    }
</style>
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.colors.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.emoji.min.css')) }}">
<link rel="stylesheet" href="{{ asset('vendors/css/tinymce/content.min.css') }}">

@endsection

@section('content')

<section id="basic-vertical-layouts">
  <!--   <form action="{{ route('template.spaf.update', $template) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @method('put')
        @csrf -->

            <!-- create question -->
            @if(! in_array($template->type, App\Models\Template::$forReport))
            <div class="row match-height">
                <div class="col-lg-4 col-sm-12">
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                              <h4 class="card-title">Add & Edit Your Questions</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        @if(! in_array($template->type, App\Models\Template::$forReport))
                                        <button class="btn btn-primary modal_button" type="button" data-action="{{ route('template.group.create', $template) }}"><i data-feather="plus"></i> Add Group / Question</button>
                                        @endif
                                        <div class="row mt-1">
                                            <div class="col-md-12 col-sm-12" id="">
                                              <div class="list-group"role="tablist"  id="sortable_groups">
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- create question -->
                <!-- show question -->
                <div class="col-lg-8 col-sm-12" id="printThis">
                    <div class="card">
                            <div class="card-header">
                              <h4 class="card-title text-center">Template Preview</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row" id="template_preview">

                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- show question -->
            </div>
            @else
            <div class="row match-height">
                <div class="col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Report Template</h4>
                        </div>
                        @php
                            $group = $template->groups->first();
                        @endphp
                        <div class="card-body">
                            
                          <div class="row mb-2">
                            <div class="col-lg-7">
                            <form action="{{ route('template.group.update', ['group' => $group]) }}" method="POST" class="form" enctype='multipart/form-data'>
                              @method('put')
                              @csrf
                             <!-- <div class="form-body"> -->
                                <input type="hidden" name="question[0][question_id]" value="{{ $group->questions->first()->id }}">
                                <input type="hidden" name="question[0][type]" value="editor">
                                <input type="hidden" name="header" placeholder="Header" class="form-control" value="{{ $group->header }}">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                          <textarea class="form-control tinymce" name="question[0][text]">{{ $group->questions->first()->text }}</textarea>
                                        </div>
                                        <div class="col-12 align-items-center justify-content-center text-center">
                                          <input type="submit" name="no_action" class="btn btn-primary me-1 btn_save" value="Save">
                                        </div>
                                    </div>
                                <!-- </div> -->
                               </form>
                              </div>
                            <div class="col-lg-5" id="forInsertion">
                                @include('app.template.group.forInsertionModal')
                            </div>
                          </div> 
                        </div>
                    </div>
                </div>
            </div>
            @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                              <!-- <button type="submit" class="btn btn-primary me-1 btn_save">Submit</button> -->
                              <a href="{{ route('template.spaf.index', ['type' => $template->type]) }}" class="btn btn-outline-secondary me-1"><i data-feather="arrow-left"></i> Go Back</a>
                              <button class="btn btn-primary btn_print" type="button"><i data-feather="printer"></i> Print</button>
                              @if(! in_array($template->type, App\Models\Template::$forReport))
                              <button class="btn btn-primary modal_button" type="button" data-action="{{ route('template.group.create', $template) }}"><i data-feather="plus"></i> Add Group / Question</button>
                              @endif
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <!-- </form> -->
</section>
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/jquery/jquery-ui.js') }}"></script>
    <!-- <script src="https://cdn.tiny.cloud/1/a5smub4os8441k8y5xnvbx7yfkdfef86of7gmmi26fi19sak/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@1/dist/tinymce-jquery.min.js"></script> -->
    <script src="{{ asset(mix('vendors/js/trumbowyg/trumbowyg.min.js')) }}"></script>
    <script src="{{ asset('vendors/js/trumbowyg/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('vendors/js/trumbowyg/trumbowyg.emoji.min.js') }}"></script>
    <script src="{{ asset('vendors/js/trumbowyg/trumbowyg.fontsize.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js" integrity="sha512-hMjDyb/4G3SapFEM71rK+Gea0+ZEr9vDlhBTyjSmRjuEgza0Ytsb67GE0aSpRMYW++z6kZPPcnddwlUG6VKm9w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script src="{{ asset('js/scripts/tables/table-question.js') }}"></script>
    <script type="text/javascript">
        function replaceIcons(){
            if (feather) {
              feather.replace({
                width: 14, height: 14
              });
            }
        }
        function loadPreview(){
            $.ajax({
              url: "{{ route('template.spaf.preview', $template) }}",
              method: "GET",
              success:function(result)
              {
                $("#template_preview").html(result);
                replaceIcons();
              }
          });
        }

        function loadGroups(){
            $.ajax({
              url: "{{ route('template.group.preview', $template) }}",
              method: "GET",
              success:function(result)
              {
                $("#sortable_groups").html(result);
                replaceIcons();
                $('[data-bs-toggle="tooltip"]').tooltip({container: 'section', trigger: 'hover'}).on('click', function() {
                    $(this).tooltip('hide')
                });
              }
          });
        }
        $(document).ready(function(){
            loadPreview();
            loadGroups();

            $("#sortable_groups").sortable({
              handle: ".ui-icon",
              items: "li.group-list:not(.disable-sort-item)",
              cancel: ".disable-sort-item",
              update: function (event, ui){
                $.ajax({
                  url: "{{ route('template.group.updateSort', $template) }}",
                  method: "POST",
                  data: {
                    sort : $('#sortable_groups').sortable('toArray')
                  },
                  success:function(result)
                  {
                    loadPreview();
                  }
                });
              }
            });

            $('.view_modal').on('hidden.bs.modal', function () {
              loadPreview();
              loadGroups();
            });

            $('.btn_print').click(function(){
                $('#printThis').printThis();
            });

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
                    }else if (self.is("div")) {
                        text = self.html();
                    }
                    // override
                    if(self.hasClass('withDataInsertion')){
                        text = self.data('forinsertion');
                        console.log(text);
                    }
                    tinymce.activeEditor.execCommand('mceInsertContent', false, text);
                    // $('#selectVariableModal').modal('toggle');
                });
                $('#selectVariableModalButton').click(function(){
                    // $('#selectVariableModal').modal('toggle');
                });

                $('input,textarea').attr('readonly', 'readonly');
                $('#forInsertion input:not([checkstate="true"])').attr('disabled', true);

              });
        });
    </script>
@endsection
