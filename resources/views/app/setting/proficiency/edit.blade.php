@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('proficiency.update', ['proficiency' => $proficiency]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Skillset / Competency
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
                          <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $proficiency->name }}">
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
