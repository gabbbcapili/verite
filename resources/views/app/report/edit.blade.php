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
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('report.update', $report) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
                @method('put')
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-4">
                        <button  type="button" class="btn btn-primary" id="selectVariableModalButton">Select Variable</button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-10">
                        <textarea class="form-control tinymce" name="content" id="content">{!! $report->content !!}</textarea>
                    </div>
                    
                </div>
                <div class="row mb-5">
                  <div class="col-lg-6">
                    @if(isset($audit))
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAuditStandard">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionAuditStandard" aria-expanded="false" aria-controls="accordionAuditStandard">
                            Audit Standard
                          </button>
                        </h2>
                        <div id="accordionAuditStandard" class="accordion-collapse collapse" aria-labelledby="headingAuditStandard" data-bs-parent="#accordionVariables">
                          <div class="accordion-body">
                            @foreach($standards as $standard)
                              <div class="row mb-2">
                                  <div class="col-lg-12 col-md-12 col-sm-12">
                                      <div class="card">
                                          <div class="card-header">
                                            <h6 class=" text-center">{{ $standard->name }}</h6>
                                            <div class="heading-elements">
                                              <ul class="list-inline mb-0">
                                                <li>
                                                  <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                </li>
                                              </ul>
                                            </div>
                                          </div>
                                          <div class="card-content collapse">
                                            <div class="card-body">
                                                @foreach($audit->forms as $auditForm)
                                                  @if($auditForm->isMultiple)
                                                    <div class="row mb-2">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                  <h6 class=" text-center">SUMMARY - {{ $auditForm->template->name }}</h6>
                                                                  <div class="heading-elements">
                                                                    <ul class="list-inline mb-0">
                                                                      <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                      </li>
                                                                    </ul>
                                                                  </div>
                                                                </div>
                                                                <div class="card-content collapse">
                                                                  <div class="card-body">
                                                                      {!! $auditForm->summarizeAnswers($standard->id) !!}
                                                                  </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  @endif
                                                  <div class="row mb-2">
                                                      <div class="col-lg-12 col-md-12 col-sm-12">
                                                          <div class="card">
                                                              <div class="card-header">
                                                                <h4 class="card-title text-center">{{ $auditForm->template->name }}</h4>
                                                                <div class="heading-elements">
                                                                  <ul class="list-inline mb-0">
                                                                    <li>
                                                                      <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                  </ul>
                                                                </div>
                                                              </div>
                                                              <div class="card-content collapse">
                                                                  @if(! $auditForm->isMultiple)
                                                                  <div class="card-body">
                                                                      <div class="row mb-2">
                                                                          @php
                                                                              $answers = $auditForm->headers->count() > 0 ? $auditForm->headers->first()->answers : null;
                                                                          @endphp
                                                                          @if($answers)
                                                                              <h4>{{ $auditForm->headers->first()->name }}</h4>
                                                                          @endif
                                                                          @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $answers, 'appliedStandard' => $standard->id])
                                                                      </div>
                                                                  </div>
                                                                  @else
                                                                      <div class="card-body">
                                                                        @foreach($auditForm->headers as $header)
                                                                          <div class="row mb-2">
                                                                            @php
                                                                                $answers = $header->answers
                                                                            @endphp
                                                                            @if($answers)
                                                                                <h4>{{ $header->name }}</h4>
                                                                            @endif
                                                                            @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $answers, 'appliedStandard' => $standard->id])
                                                                        </div>
                                                                        @endforeach
                                                                      </div>
                                                                  @endif
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              @endforeach
                                            </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                      @endif
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
@include('app.template.group.forInsertionModal')
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
                  height : "700"
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
                $('#selectVariableModal').modal('hide');
            });
            $('#selectVariableModalButton').click(function(){
                $('#selectVariableModal').modal('toggle');
            });

            $('input,textarea').attr('readonly', 'readonly');

          });
    </script>
@endsection
