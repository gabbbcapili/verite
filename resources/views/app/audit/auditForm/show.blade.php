@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Audit Form' )

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div id="printThis">
        <div class="row mb-2">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <!-- <div class="card-header"> -->
                      <!-- <h4 class="card-title text-center">Template Preview</h4> -->
                    <!-- </div> -->
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row mb-2" id="template_preview">
                                @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $auditFormHeader->answers, 'disabled' => true])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-print">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                              <button type="button" class="btn btn-primary no-print btn_print"><i data-feather="printer"></i> Print </button>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('vendor-script')
    <script src="{{ asset('vendors/js/jquery/jquery-ui.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.btn_print').click(function(){
                $('#printThis').printThis();
            });
        });
    </script>
@endsection
