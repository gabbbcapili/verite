@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('settings.country.update', ['country' => $country]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Country
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
                          <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $country->name }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Acronym:</label>
                          <input type="text" class="form-control" name="acronym" placeholder="Acronym" value="{{ $country->acronym }}">
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Timezone:</label>
                          <select class="form-control select2Modal" name="timezone">
                            @foreach(App\Models\Country::$timezones as $timezone)
                              <option value="{{ $timezone }}" {{ $country->timezone == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
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
      $('.select2Modal').select2({
        dropdownParent: $("#view_modal")
      });
    });
</script>
