@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('settings.scheduleStatus.update', ['scheduleStatus' => $scheduleStatus]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Schedule Status
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="form-body">
                  <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Name:</label>
                          <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $scheduleStatus->name }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                       <label class="form-label">Color</label>
                       <select  class="form-control select2" name="color" title="Choose color">
                        <option selected disabled></option>
                        @foreach(App\Models\ScheduleStatus::$colors as $color)
                            <option value="{{ $color}}" data-color="{{ $color }}" {{ $scheduleStatus->color == $color ? 'selected' : '' }}>
                                <span class="text-{{ $color }}">{{ $color }}</span>
                            </option>
                        @endforeach
                        </select>
                  </div>
                  </div>
                   <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                         <label class="form-label">Block Users / Client / Supplier availability in this Schedule Status?</label>
                         <select  class="form-control" name="blockable">
                          <option value="1" {{ $scheduleStatus->blockable == 1 ? 'selected' : '' }}>Yes</option>
                          <option value="0" {{ $scheduleStatus->blockable == 0 ? 'selected' : '' }}>No</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-12 col-xs-12">
                        <label>Next Stop:</label>
                        <select class="form-control select2" multiple name="next_stop[]" id="next_stop">
                            @foreach($scheduleStatuses as $ss)
                                <option value="{{ $ss->id }}" {{ in_array($ss->id, $next_stop) ? 'selected' : '' }}>{{ $ss->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
      </div>
    </div>
  </form>
</div>
<script src="{{ asset(mix('js/scripts/forms-validation/form-modal.js')) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
            $(".select2").select2({
                templateResult: formatState,
                templateSelection: formatState
          });

            function formatState (opt) {
                if (!opt.id) {
                    return opt.text.toUpperCase();
                }

                var color = $(opt.element).attr('data-color');
                console.log(color)
                if(!color){
                   return opt.text.toUpperCase();
                } else {
                    var $opt = $(
                       '<span class="text-white bg-' + color + '">' + opt.text.toUpperCase() + '</span>'
                    );
                    return $opt;
                }
            };
        });
</script>
