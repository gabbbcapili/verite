@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Audit')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div id="printThis">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title text-center">Audit Details</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-end" style="width: 20%">Schedule:</th>
                                                <td style="width: 40%"><a href="#" class="modal_button" data-action="{{route('schedule.edit', $audit->schedule->event_id)}}">{{$audit->schedule->title}}</a></td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Auditee:</th>
                                                <td style="width: 40%">{!! $schedule->client->CompanyDetails !!}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Audit Model:</th>
                                                <td style="width: 40%">{{$schedule->audit_model }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Audit Model Type:</th>
                                                <td style="width: 40%">{{ $schedule->audit_model_type }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Country:</th>
                                                <td style="width: 40%">{{ $schedule->country }} - {{ $schedule->city }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Timezone:</th>
                                                <td style="width: 40%">{{ $schedule->timezone }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-end" style="width: 20%">Due Date:</th>
                                                <td style="width: 40%">{{ $schedule->due_date }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Status:</th>
                                                <td style="width: 40%">{!! $audit->statusDisplay !!}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Approved On:</th>
                                                <td style="width: 40%">{!! $audit->approved_at !!}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-end" style="width: 20%">Notes:</th>
                                                <td style="width: 40%">{!! $audit->notes !!}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        @foreach($schedule->event->users as $u)
                                            <tr>
                                                <th class="text-end" style="width: 20%">{{ $u->role }}:</th>
                                                <td style="width: 40%">{{ $u->modelable->fullName }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $answers, 'disabled' => true])
                                </div>

                                @if($audit->status != 'completed')
                                    @if(! $auditForm->headers->count() > 0)
                                        <div class="row">
                                            <div class="col-12 align-items-center justify-content-center text-center">
                                                <a href="{{ route('auditForm.create', $auditForm) }}" class="btn btn-outline-secondary">Edit <i data-feather="arrow-right"></i></a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-12 align-items-center justify-content-center text-center">
                                                <a href="{{ route('auditForm.edit', $auditForm->headers->first()) }}" class="btn btn-outline-secondary">Edit <i data-feather="arrow-right"></i></a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @else
                                <div class="card-body">
                                    <div class="row mb-2 align-items-center justify-content-center text-center">
                                      <div class="col-lg-6 col-sm-12">
                                            @if($audit->status != 'completed')
                                              <div class="d-flex justify-content-end mb-1 no-print">
                                                <a class="btn btn-primary" href="{{ route('auditForm.create', $auditForm) }}"><i data-feather="plus-circle"></i> Add Answer</a>
                                              </div>
                                            @endif
                                          <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <th>Assigned Name</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($auditForm->headers as $header)
                                                        <tr id="header{{ $header->id }}">
                                                            <td>{{ $header->name }}</td>
                                                            <td>@if($audit->status != 'completed')
                                                                    <a target="_blank" href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" data-href="http://127.0.0.1:8000/auditForm/12" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
                                                                    {!! App\Models\Utilities::actionButtons([
                                                                        ['route' => route('auditForm.edit', $header), 'name' => 'Edit', 'type' => 'href'],
                                                                        ['route' => route('auditForm.destroy', $header), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit form answer?', 'text' => 'Delete']
                                                                    ]); !!}
                                                                @else
                                                                    <a target="_blank" href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" data-href="http://127.0.0.1:8000/auditForm/12" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
                                                                @endif
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                      </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row no-print">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                              @if(in_array($audit->status, ['pending', 'additional', 'answered']) && (request()->user()->hasRole('Supplier') || request()->user()->hasRole('Client')))
                                <a href="{{ route('audit.edit', $audit) }}" class="btn btn-outline-secondary">Edit <i data-feather="arrow-right"></i></a>
                              @endif
                              @if(request()->user()->can('audit.approve') && in_array($audit->status, ['pending']))
                                <!-- <a data-action="{{ route('audit.approve', ['audit' => $audit, 'approve' => false]) }}" data-confirmbutton="Disapprove" data-title="Are you sure to DISAPPROVE this Audit?" class="btn btn-danger confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="x-circle"></i> Disapprove</a> -->
                                <a data-action="{{ route('audit.approve', ['audit' => $audit, 'approve' => true]) }}" data-confirmbutton="Approve" data-title="Are you sure to APPROVE this Audit?" class="btn btn-success confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="check-circle"></i> Approve</a>
                              @endif
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
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.btn_print').click(function(){
                $('#printThis').printThis();
            });
        });
    </script>
@endsection
