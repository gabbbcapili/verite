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
                                        <td class="forInsertion">{{ isset($company) ? $company->company_name : '{company-company_name}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Acronym</th>
                                        <td class="forInsertion">{{ isset($company) ? $company->acronym : '{company-acronym}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Address</th>
                                        <td class="forInsertion">{{ isset($company) ? $company->address : '{company-address}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Contact Number</th>
                                        <td class="forInsertion">{{ isset($company) ? $company->contact_number : '{company-contact_number}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Website</th>
                                        <td class="forInsertion">{{ isset($company) ? $company->website : '{company-website}' }}</td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          @if(isset($spafs))
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                SPAF
                              </button>
                            </h2>
                            <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                <div class="accordion" id="accordionSpafs">
                                  @foreach($spafs as $spaf)
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSpafs{{ $spaf->id }}">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTargetSpafs{{ $spaf->id }}" aria-expanded="false" aria-controls="accordionTargetSpafs{{ $spaf->id }}">
                                        <h4>{{ $spaf->template->name }}</h4>
                                      </button>
                                    </h2>
                                    <div id="accordionTargetSpafs{{ $spaf->id }}" class="accordion-collapse collapse" aria-labelledby="headingSpafs{{ $spaf->id }}" data-bs-parent="#accordionSpafs">
                                      <div class="accordion-body">
                                            @include('app.template.spaf.preview', ['template' => $spaf->template, 'answers' => $spaf->answers])
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                          @endif
                          @if(isset($templates))
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                SPAF
                              </button>
                            </h2>
                            <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                <div class="accordion" id="accordionTemplates">
                                  @foreach($templates as $template)
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTemplates{{ $template->id }}">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTargetTemplates{{ $template->id }}" aria-expanded="false" aria-controls="accordionTargetTemplates{{ $template->id }}">
                                        <h4>{{ $template->name }}</h4>
                                      </button>
                                    </h2>
                                    <div id="accordionTargetTemplates{{ $template->id }}" class="accordion-collapse collapse" aria-labelledby="headingTemplates{{ $template->id }}" data-bs-parent="#accordionTemplates">
                                      <div class="accordion-body">
                                            @include('app.template.spaf.preview', ['template' => $template, 'answers' => null])
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                          @endif
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
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->event->start_date : '{event-start_date}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>End Date</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->event->end_date : '{event-end_date}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Title</th>

                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->title : '{schedule-title}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Audit Model</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->audit_model : '{schedule-audit_model}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Audit Model Type</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->audit_model_type : '{schedule-audit_model_type}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Status</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->status : '{schedule-status}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>City</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->city : '{schedule-city}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>With Completed SPAF?</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->with_completed_spaf ? 'Yes' : '' : '{schedule-with_completed_spaf}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>With Quotation?</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->with_quotation ? 'Yes' : '' : '{schedule-with_quotation}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Due Date</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->due_date : '{schedule-due_date}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>Report Submitted</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->report_submitted : '{schedule-report_submitted}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_1 }}</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->cf_1 : '{schedule-cf_1}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_2 }}</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->cf_2 : '{schedule-cf_2}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_3 }}</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->cf_3 : '{schedule-cf_3}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_4 }}</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->cf_4 : '{schedule-cf_4}' }}</td>
                                      </tr>
                                      <tr>
                                        <th>{{ $settings->schedule_cf_5 }}</th>
                                        <td class="forInsertion">{{ isset($schedule) ? $schedule->cf_5 : '{schedule-cf_5}' }}</td>
                                      </tr>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          @if(isset($audit))
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                                Audit
                              </button>
                            </h2>
                            <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                @foreach($audit->forms as $auditForm)
                                  @if($auditForm->isMultiple)
                                    <div class="row mb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                  <h4 class="card-title text-center">SUMMARY - {{ $auditForm->template->name }}</h4>
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
                                                      {!! $auditForm->summarizeAnswers() !!}
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
                          @endif
                           @if(isset($templatesReport))
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTen">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTen" aria-expanded="false" aria-controls="accordionTen">
                                Report
                              </button>
                            </h2>
                            <div id="accordionTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#accordionVariables">
                              <div class="accordion-body">
                                <div class="accordion" id="accordionTemplatesReport">
                                  @foreach($templatesReport as $template)
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTemplatesReport{{ $template->id }}">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTargetTemplatesReport{{ $template->id }}" aria-expanded="false" aria-controls="accordionTargetTemplatesReport{{ $template->id }}">
                                        <h4>{{ $template->name }}</h4>
                                      </button>
                                    </h2>
                                    <div id="accordionTargetTemplatesReport{{ $template->id }}" class="accordion-collapse collapse" aria-labelledby="headingTemplatesReport{{ $template->id }}" data-bs-parent="#accordionTemplatesReport">
                                      <div class="accordion-body">
                                            @include('app.template.spaf.preview', ['template' => $template, 'answers' => null])
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                          @endif
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
