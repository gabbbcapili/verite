@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-lg">
    <form action="{{ route('supplier.storeContact', ['company' => $company]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('post')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add Contact Person to {{ $company->company_name }}
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
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Type:</label>
                        <select class="form-control" name="type" id="creationType">
                          <option>Create New</option>
                          <option>Select from List</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="createNew">
                    <div class="row mb-2">
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" name="first_name" placeholder="First Name">
                        </div>
                      </div>
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="addExising" class="d-none">
                    <div class="row mb-2">
                      <div class="col-lg-12">
                        <label>Contact Persons:</label>
                        <select class="form-control select2Modal" name="users[]" multiple>
                          @foreach($users as $user)
                            @if(! in_array($user->id, $company->users()->pluck('users.id')->toArray()))
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endif
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

      $('#creationType').change(function(){
        var value = $(this).find(":selected").val();
        if(value == 'Create New'){
          $('#createNew').removeClass('d-none');
          $('#addExising').addClass('d-none');
        }else{
          $('#createNew').addClass('d-none');
          $('#addExising').removeClass('d-none');
        }
      });
    });
</script>
