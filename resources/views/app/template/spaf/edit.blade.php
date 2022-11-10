@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Edit '. $template->name .' Template')

@section('vendor-style')
<style type="text/css">
    .ui-sortable-helper {
        display: table;
    }
</style>
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <form action="{{ route('template.spaf.update', $template) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="row match-height">
            <!-- create question -->
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                          <h4 class="card-title">Add & Edit Your Questions</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <button class="btn btn-primary modal_button" type="button" data-action="{{ route('template.group.create', $template) }}"><i data-feather="plus"></i> Add Group / Question</button>
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
                <div class="col-lg-8 col-md-8 col-sm-12">
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                              <!-- <button type="submit" class="btn btn-primary me-1 btn_save">Submit</button> -->
                              <a href="{{ route('template.spaf.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Go Back</a>
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
    <script src="{{ asset('vendors/js/jquery/jquery-ui.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>

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

                  }
                });
              }
            });

            $('.view_modal').on('hidden.bs.modal', function () {
              loadPreview();
              loadGroups();
            });

        });


    </script>
@endsection
