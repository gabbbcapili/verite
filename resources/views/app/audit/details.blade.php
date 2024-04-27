@inject('request', 'Illuminate\Http\Request')
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