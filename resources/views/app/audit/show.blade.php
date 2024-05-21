@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Audit')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div id="printThis">
        @include('app.audit.details');
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
                          <h4 class="card-title text-center">{{ $auditForm->template->name }} {{ $auditForm->template->audit_type }} - {!! $auditForm->getTypeDisplay() !!}</h4>
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
                                                <a class="btn btn-primary" href="{{ route('auditForm.create', ['auditForm' => $auditForm, 'template' => $auditForm->template->slug, 'q' => 'p', 'single_multiple' => $auditForm->isMultiple ? 'Multiple' : 'Single' ,'type' => $auditForm->template->audit_type, 'assigned_name' => 'null']) }}"><i data-feather="plus-circle"></i> Add Answer</a>
                                              </div>
                                            @else
                                            
                                                @if(! $auditForm->headers->count() > 0)
                                                    <div class="d-flex justify-content-end mb-1 no-print">
                                                        <a class="btn btn-primary" href="{{ route('auditForm.create', ['auditForm' => $auditForm, 'template' => $auditForm->template->slug, 'q' => 'p', 'single_multiple' => $auditForm->isMultiple ? 'Multiple' : 'Single' ,'type' => $auditForm->template->audit_type, 'assigned_name' => 'null']) }}"><i data-feather="plus-circle"></i> Add Answer</a>
                                                      </div>
                                                @endif
                                            @endif
                                        @endif
                                      <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Assigned Name</th>
                                                <th>Status</th>
                                                <th>Review Status</th>
                                                <th>Completion</th>
                                                <th>Created</th>
                                                <th>Updated</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($auditForm->headers as $header)
                                                    @if(! $request->user()->can('audit.all_records'))
                                                        @if($request->user()->id != $header->created_by)
                                                            @continue
                                                        @endif
                                                    @endif
                                                    @php $reviewCount = $header->reviews()->where('status', 'pending')->count(); @endphp
                                                    <tr id="header{{ $header->id }}">
                                                        <td>{{ $header->name }}</td>
                                                        <td>{!! $header->statusDisplay !!}</td>
                                                        <td>
                                                            @if($reviewCount)
                                                                <span class="text-danger">With Pending Reviews ({{ $reviewCount }})</span>
                                                            @else
                                                                <span class="text-secondary">No Pending Review</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $header->groupCompleted }} / {{$auditForm->template->groups->count()}} groups</td>
                                                        <td>{{ $header->created_at->format('M d, Y') . ' | ' . $header->createdByName }}</td>
                                                        <td>{{ $header->updated_at->diffForHumans() . ' | ' . $header->updatedByName }}</td>
                                                        <td>
                                                            @if($audit->status != 'completed' && $header->status != 'approved')
                                                                @if($request->user()->can('auditForm.view'))
                                                                    <a href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
                                                                @endif
                                                                @if($auditForm->isMultiple)
                                                                    @if($request->user()->can('auditForm.edit'))
                                                                        {!! App\Models\Utilities::actionButtons([
                                                                            ['route' => route('auditForm.edit', ['auditFormHeader' => $header, 'template' => $auditForm->template->slug, 'q' => 'p', 'assigned_name' => $header->name, 'single_multiple' => $auditForm->isMultiple ? 'Multiple' : 'Single' ,'type' => $auditForm->template->audit_type]), 'name' => 'Edit', 'type' => 'href'],
                                                                        ]); !!}
                                                                    @endif
                                                                    @if($request->user()->can('auditForm.delete'))
                                                                        {!! App\Models\Utilities::actionButtons([
                                                                            ['route' => route('auditForm.destroy', $header), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit form answer?', 'text' => 'Delete']
                                                                        ]); !!}
                                                                    @endif
                                                                @else
                                                                    @if($request->user()->can('auditForm.edit'))
                                                                        {!! App\Models\Utilities::actionButtons([
                                                                            ['route' => route('auditForm.edit', ['auditFormHeader' => $header, 'template' => $auditForm->template->slug, 'q' => 'p', 'assigned_name' => $header->name, 'single_multiple' => $auditForm->isMultiple ? 'Multiple' : 'Single' ,'type' => $auditForm->template->audit_type]), 'name' => 'Edit', 'type' => 'href']
                                                                        ]); !!}
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if($request->user()->can('auditForm.view'))
                                                                    <a target="_blank" href="{{ route('auditForm.show', $header) }}" data-bs-toggle="tooltip" data-placement="top" title="" class="me-50" data-bs-original-title="Show" aria-label="Show"><i data-feather="eye"></i></a>
                                                                @endif
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
                              @if(request()->user()->can('auditForm.modifyForms'))
                                  
                                  @if($request->user()->can('audit.manage') && in_array($audit->status, ['pending']))
                                    <a href="{{ route('audit.forms', ['audit' => $audit]) }}" class="btn btn-info"><i data-feather="edit"></i> Modify Forms</a>
                                  @endif
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
