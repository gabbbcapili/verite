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
                                                <td style="width: 40%">
                                                    @if($request->user()->can('schedule.manage'))
                                                        <a href="#" class="modal_button" data-action="{{route('schedule.edit', $audit->schedule->event_id)}}">{{$audit->schedule->title}}</a>
                                                    @else
                                                        {{$audit->schedule->title}}
                                                    @endif
                                                </td>
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
                                                <th class="text-end" style="width: 20%">Completed At:</th>
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
            @if($auditForm->template->audit_type)
                @if(! $request->user()->can(App\Models\Template::$auditTypes[$auditForm->template->audit_type]))
                    @continue
                @endif
            @endif
            <div class="row mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                          <h4 class="card-title text-center">{{ $auditForm->template->name }} {{ $auditForm->template->audit_type }} - <span class="text-{{$auditForm->isMultiple ? 'danger' : 'info'}}">[{{$auditForm->isMultiple ? 'Multiple' : 'One Time'}}]</span></h4>
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
                                <div class="row mb-2 align-items-center justify-content-center text-center">
                                  <div class="col-lg-6 col-sm-12">
                                        @if($audit->status != 'completed')
                                            @if($auditForm->isMultiple)
                                              <div class="d-flex justify-content-end mb-1 no-print">
                                                <a class="btn btn-primary" href="{{ route('auditForm.create', ['auditForm' => $auditForm, 'template' => $auditForm->template->slug]) }}"><i data-feather="plus-circle"></i> Add Answer</a>
                                              </div>
                                            @else
                                                @if(! $auditForm->headers->count() > 0)
                                                    <div class="d-flex justify-content-end mb-1 no-print">
                                                        <a class="btn btn-primary" href="{{ route('auditForm.create', ['auditForm' => $auditForm, 'template' => $auditForm->template->slug]) }}"><i data-feather="plus-circle"></i> Add Answer</a>
                                                      </div>
                                                @endif
                                            @endif
                                        @endif
                                      <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Assigned Name</th>
                                                <th>Status</th>
                                                <th>Completion</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($auditForm->headers as $header)
                                                    <tr id="header{{ $header->id }}">
                                                        <td>{{ $header->name }}</td>
                                                        <td>{!! $header->statusDisplay !!}</td>
                                                        <td>{{ $header->groupCompleted }} / {{$auditForm->template->groups->count()}} groups</td>
                                                        <td>@if($audit->status != 'completed' && $header->status != 'approved')
                                                                <a target="_blank" href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
                                                                @if($auditForm->isMultiple)
                                                                    {!! App\Models\Utilities::actionButtons([
                                                                        ['route' => route('auditForm.edit', ['auditFormHeader' => $header, 'template' => $auditForm->template->slug, 'assigned_name' => $header->name, 'type' => $auditForm->template->type]), 'name' => 'Edit', 'type' => 'href'],
                                                                        ['route' => route('auditForm.destroy', $header), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit form answer?', 'text' => 'Delete']
                                                                    ]); !!}
                                                                @else
                                                                    {!! App\Models\Utilities::actionButtons([
                                                                        ['route' => route('auditForm.edit', ['auditFormHeader' => $header, 'template' => $auditForm->template->slug, 'assigned_name' => $header->name, 'type' => $auditForm->template->type]), 'name' => 'Edit', 'type' => 'href']
                                                                    ]); !!}
                                                                @endif
                                                            @else
                                                                <a target="_blank" href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
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
                              @if(request()->user()->can('audit.approve') && in_array($audit->status, ['pending']))
                                <!-- <a data-action="{{ route('audit.approve', ['audit' => $audit, 'approve' => false]) }}" data-confirmbutton="Disapprove" data-title="Are you sure to DISAPPROVE this Audit?" class="btn btn-danger confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="x-circle"></i> Disapprove</a> -->
                                <a data-action="{{ route('audit.approve', ['audit' => $audit, 'approve' => true]) }}" data-confirmbutton="Complete Audit" data-title="Are you sure to COMPLETE this Audit?" class="btn btn-success confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="check-circle"></i> Complete Audit</a>
                              @elseif(request()->user()->can('audit.approve') && in_array($audit->status, ['completed']))
                                <a data-action="{{ route('audit.approve', ['audit' => $audit, 'approve' => false]) }}" data-confirmbutton="Revert to Pending" data-title="Are you sure to revert this audit to pending?" class="btn btn-warning confirm"><i data-feather="x-circle"></i> Revert to Pending</a>
                              @endif
                              <button type="button" class="btn btn-primary no-print btn_print"><i data-feather="printer"></i> Print </button>
                              @if($request->user()->can('audit.manage') && in_array($audit->status, ['pending']))
                                <a href="{{ route('audit.createForm', ['audit' => $audit]) }}" class="btn btn-info"><i data-feather="plus-circle"></i> Add Forms</a>
                              @endif

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
