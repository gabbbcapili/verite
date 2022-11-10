@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-lg">
    <form action="{{ route('user.update', ['user' => $user]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit User
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
                          <label for="name">First Name:</label>
                          <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ $user->first_name }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Last Name:</label>
                          <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Email:</label>
                          <input type="text" class="form-control" name="email" placeholder="Email" value="{{ $user->email }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Change Password:</label>
                          <input type="text" class="form-control" name="password" placeholder="Change Password">
                      </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-6 col-xs-12">
                      <div class="form-group">
                          <label for="name">Role:</label>
                          <select class="form-control select2Modal" name="role">
                            @foreach($roles as $role)
                              <option value="{{ $role->name }}" {{ $user->getRoleNames()->first() == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
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
