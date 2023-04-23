@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          View Audit Program
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <tr>
                          <th>Id</th>
                          <th>Copy From Schedule</th>
                          <th>Start Date</th>
                          <th>Frequency (Months)</th>
                          <th>Length (Months)</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>{{ $auditProgram->id }}</td>
                            <td><a href="#" class="modal_button" data-action="{{route('schedule.edit', $auditProgram->schedule->event->id)}}">{{$auditProgram->schedule->title}}</a></td>
                            <td>{{ $auditProgram->start_date }}</td>
                            <td>{{ $auditProgram->frequency }}</td>
                            <td>{{ $auditProgram->length }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <tr>
                          <th>Schedule</th>
                          <th>Plot Date</th>
                          <th>Plotted</th>
                          <th>Updated At</th>
                        </tr>
                        <tbody>
                          @foreach($auditProgram->auditProgramDates as $d)
                          <tr>
                            @if($d->schedule)
                            <td><a href="#" class="modal_button" data-action="{{route('schedule.edit', $auditProgram->schedule->event->id)}}">{{$auditProgram->schedule->title}}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $d->plot_date }}</td>
                            <td>{{ $d->plotted ? 'Yes' : 'No' }}</td>
                            <td>{{ $d->updated_at }}</td>
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
      <div class="modal-footer">

      </div>
    </div>
</div>
