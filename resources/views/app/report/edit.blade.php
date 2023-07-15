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
                    <div class="col-4">
                        <button  type="button" class="btn btn-primary" id="selectVariableModalButton">Select Variable</button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-10">
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
    </div>
<!-- Modal -->
<div class="modal fade" id="selectVariableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Select Variable To Insert
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-12">
                         <div class="accordion" id="accordionVariables">
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="false" aria-controls="accordionOne">
                                Company Details
                              </button>
                            </h2>
                            <div id="accordionOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                <div class="row">
                                  <div class="col-12">
                                    <table class="table table-striped">
                                      <tr>
                                        <th>Company Name</th>
                                        <td class="forInsertion">{{ $company->company_name }}</td>
                                      </tr>
                                      <tr>
                                        <th>Acronym</th>
                                        <td class="forInsertion">{{ $company->acronym }}</td>
                                      </tr>
                                      <tr>
                                        <th>Address</th>
                                        <td class="forInsertion">{{ $company->address }}</td>
                                      </tr>
                                      <tr>
                                        <th>Contact Number</th>
                                        <td class="forInsertion">{{ $company->contact_number }}</td>
                                      </tr>
                                      <tr>
                                        <th>Website</th>
                                        <td class="forInsertion">{{ $company->website }}</td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                SPAF
                              </button>
                            </h2>
                            <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                @foreach($spafs as $spaf)
                                    @include('app.template.spaf.preview', ['template' => $spaf->template, 'answers' => $spaf->answers])
                                @endforeach
                              </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                Schedule
                              </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                <div class="row">
                                  <div class="col-12">
                                    <table class="table table-striped">
                                      <tr>
                                        <th>Start Date</th>
                                        <td class="forInsertion">{{ $schedule->event->start_date }}</td>
                                      </tr>
                                      <tr>
                                        <th>End Date</th>
                                        <td class="forInsertion">{{ $schedule->event->end_date }}</td>
                                      </tr>
                                      <tr>
                                        <th>Title</th>
                                        <td class="forInsertion">{{ $schedule->title }}</td>
                                      </tr>
                                      <tr>
                                        <th>Audit Model</th>
                                        <td class="forInsertion">{{ $schedule->audit_model }}</td>
                                      </tr>
                                      <tr>
                                        <th>Audit Model Type</th>
                                        <td class="forInsertion">{{ $schedule->audit_model_type }}</td>
                                      </tr>
                                      <tr>
                                        <th>Status</th>
                                        <td class="forInsertion">{{ $schedule->status }}</td>
                                      </tr>
                                      <tr>
                                        <th>City</th>
                                        <td class="forInsertion">{{ $schedule->city }}</td>
                                      </tr>
                                      <tr>
                                        <th>With Completed SPAF?</th>
                                        <td class="forInsertion">{{ $schedule->with_completed_spaf ? 'Yes' : '' }}</td>
                                      </tr>
                                      <tr>
                                        <th>With Quotation?</th>
                                        <td class="forInsertion">{{ $schedule->with_quotation ? 'Yes' : '' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Due Date</th>
                                        <td class="forInsertion">{{ $schedule->due_date }}</td>
                                      </tr>
                                      <tr>
                                        <th>Report Submitted</th>
                                        <td class="forInsertion">{{ $schedule->report_submitted }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_1 }}</th>
                                        <td class="forInsertion">{{ $schedule->cf_1 }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_2 }}</th>
                                        <td class="forInsertion">{{ $schedule->cf_2 }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_3 }}</th>
                                        <td class="forInsertion">{{ $schedule->cf_3 }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_4 }}</th>
                                        <td class="forInsertion">{{ $schedule->cf_4 }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_5 }}</th>
                                        <td class="forInsertion">{{ $schedule->cf_5 }}</td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                                Audit
                              </button>
                            </h2>
                            <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                @foreach($audit->forms as $auditForm)
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
                                                          @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $answers])
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
                                                            @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $answers])
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary no-print btn_save" id="saveBtn"><i data-feather="save"></i> Save
          </button>
      </div>
    </div>
  </div>
</div>
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
                }
                tinymce.activeEditor.execCommand('mceInsertContent', false, text);
                $('#selectVariableModal').modal('toggle');
            });
            $('#selectVariableModalButton').click(function(){
                $('#selectVariableModal').modal('toggle');
            });

            $('input,textarea').attr('readonly', 'readonly');

          });
    </script>
@endsection
