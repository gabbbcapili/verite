@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('auditForm.review.store', $auditFormHeader) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('post')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add Review
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2">
            <div class="col-lg-6">
                <label>Target Group:</label>
                <select class="form-control select2Modal" name="group_id">
                    <option disabled selected></option>
                    @foreach($targets as $target)
                        <option value="{{ $target->id }}">{{ $target->header }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label>Message</label>
                <textarea class="form-control" name="message" rows="5"></textarea>
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
