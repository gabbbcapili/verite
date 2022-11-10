@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        View Role
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-6">
                  <label class="form-label">Role Name:</label>
                  <input type="text" name="name" placeholder="Role Name" class="form-control" value="{{ $role->name }}" viewonly="true">
                </div>
              </div>
              @include('app.role.permissions', ['roleList' => $roleList])
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">

    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      $('input[name="permissions[]"]').attr('disabled', 'disabled');
    });
</script>
