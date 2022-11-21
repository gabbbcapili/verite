<div class="row mb-2">
  <div class="col-12">
  <div class="table-responsive border rounded px-1">
    <h4 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i
        class="feather icon-lock mr-50 "></i>Permission </h4>
    <table class="table table-borderless">
      <thead>
        <tr>
          <th>Templates</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="template.manage" name="permissions[]" {{ isset($roleList) ? in_array('template.manage', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="file" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Template</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="template.approve" name="permissions[]" {{ isset($roleList) ? in_array('template.approve', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Approve Template</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody>
       <thead>
        <tr>
          <th>Suppliers</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="supplier.manage" name="permissions[]" {{ isset($roleList) ? in_array('supplier.manage', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="file-plus" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Suppliers</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="spaf.manage" name="permissions[]" {{ isset($roleList) ? in_array('spaf.manage', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="paperclip" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Assesment Forms</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="spaf.approve" name="permissions[]" {{ isset($roleList) ? in_array('spaf.approve', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Approve Assesment Forms</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Users</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="client.manage" name="permissions[]" {{ isset($roleList) ? in_array('client.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="award" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Clients</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Manage</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="user.manage" name="permissions[]" {{ isset($roleList) ? in_array('user.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">Users</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="role.manage" name="permissions[]" {{ isset($roleList) ? in_array('role.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-check" class="vs-icon"></i></span>
              </span>
              <span class="">Roles & Priviledges</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
