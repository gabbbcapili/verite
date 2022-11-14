@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'SPAF' )

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title text-center">Template Preview</h4> -->
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-end" style="width: 20%">Name:</th>
                                        <td style="width: 40%">{!! $spaf->user->fullName !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end" style="width: 20%">Email:</th>
                                        <td style="width: 40%">{!! $spaf->user->email !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end" style="width: 20%">Status:</th>
                                        <td style="width: 40%">{!! $spaf->statusDisplay !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end" style="width: 20%">Completed At:</th>
                                        <td style="width: 40%">{{ Carbon\Carbon::parse($spaf->completed_at)->diffForHumans() }}</td>
                                    </tr>
                                    @if($spaf->completed_at)
                                        <tr>
                                            <th class="text-end" style="width: 20%">Approved At:</th>
                                            <td style="width: 40%">{{ Carbon\Carbon::parse($spaf->approved_at)->diffForHumans() }}</td>
                                        </tr>
                                    @endif
                                    @if($spaf->completed_at)
                                        <tr>
                                            <th class="text-end" style="width: 20%">Notes:</th>
                                            <td style="width: 40%">{{ $spaf->notes }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                  <!-- <h4 class="card-title text-center">Template Preview</h4> -->
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row mb-2" id="template_preview">
                            @include('app.template.spaf.preview', ['template' => $spaf->template, 'answers' => $spaf->answers, 'disabled' => true])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-12 align-items-center justify-content-center text-center">
                          @if(request()->user()->hasRole('Supplier') && in_array($spaf->status, ['pending', 'additional', 'answered']))
                            <a href="{{ route('spaf.edit', $spaf) }}" class="btn btn-outline-secondary">Edit <i data-feather="arrow-right"></i></a>
                          @endif
                          @if(request()->user()->can('supplier.approve') && in_array($spaf->status, ['answered']))
                            <a data-action="{{ route('spaf.approve', ['spaf' => $spaf, 'approve' => false]) }}" data-confirmbutton="Disapprove" data-title="Are you sure to DISAPPROVE this spaf?" class="btn btn-danger confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="x-circle"></i> Disapprove</a>
                            <a data-action="{{ route('spaf.approve', ['spaf' => $spaf, 'approve' => true]) }}" data-confirmbutton="Approve" data-title="Are you sure to APPROVE this spaf?" class="btn btn-success confirmWithNotes" data-text="You can add notes on the input below"><i data-feather="check-circle"></i> Approve</a>
                          @endif


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
@endsection
