<div class="row mb-2">
  <div class="col-12">
  <div class="table-responsive border rounded px-1">
    <h4 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i
        class="feather icon-lock mr-50 "></i>Permission </h4>
    <table class="table table-borderless">
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
              <input type="checkbox" value="user.manage" name="permissions[]" {{ isset($roleList) ? in_array('user.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="users" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Users</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="role.manage" name="permissions[]" {{ isset($roleList) ? in_array('role.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-check" class="vs-icon"></i></span>
              </span>
              <span class="">Roles & Privileges</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Clients</th>
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
                <span class="vs-checkbox--check"><i data-feather="package" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Suppliers</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="spaf.manage" name="permissions[]" {{ isset($roleList) ? in_array('spaf.manage', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="columns" class="vs-icon"></i></span>
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
          <th>Dashboard</th>
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
              <input type="radio" value="dashboard.default" name="permissions[]" {{ isset($roleList) ? in_array('dashboard.default', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Default</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="radio" value="dashboard.scheduler" name="permissions[]" {{ isset($roleList) ? in_array('dashboard.scheduler', $roleList) ? 'checked' : '' : 'checked' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Scheduler</span>
            </div></fieldset>
          </td>
          <td></td>
          <td></td>
        </tr><tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="radio" value="dashboard.supplier" name="permissions[]" {{ isset($roleList) ? in_array('dashboard.supplier', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Supplier</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="radio" value="dashboard.client" name="permissions[]" {{ isset($roleList) ? in_array('dashboard.client', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Client</span>
            </div></fieldset>
          </td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Schedules</th>
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
              <input type="checkbox" value="schedule.manage" name="permissions[]" {{ isset($roleList) ? in_array('schedule.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="calendar" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Schedules</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.country.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.country.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="flag" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Countries</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.scheduleStatus.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.scheduleStatus.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="alert-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Statuses</span>
            </div></fieldset>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.auditModel.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.auditModel.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="trello" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Audit Models</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="schedule.selectableAuditor" name="permissions[]" {{ isset($roleList) ? in_array('schedule.selectableAuditor', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Selectable as Resource</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Audits</th>
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
              <input type="checkbox" value="audit.manage" name="permissions[]" {{ isset($roleList) ? in_array('audit.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="folder" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Audits</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.approve" name="permissions[]" {{ isset($roleList) ? in_array('audit.approve', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Approve Audits</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.modifyForms" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.modifyForms', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="edit" class="vs-icon"></i></span>
              </span>
              <span class="">Can Modify Forms</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.auditform" name="permissions[]" {{ isset($roleList) ? in_array('audit.auditform', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Audit Forms</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.wif" name="permissions[]" {{ isset($roleList) ? in_array('audit.wif', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Worker Interviewer Forms</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.mif" name="permissions[]" {{ isset($roleList) ? in_array('audit.mif', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Management Interview Forms</span>
            </div></fieldset>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.drf" name="permissions[]" {{ isset($roleList) ? in_array('audit.drf', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">Documents Review Forms</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody>

      <thead>
        <tr>
          <th>Audit Forms</th>
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
              <input type="checkbox" value="auditForm.saveandcontinue" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.saveandcontinue', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="pocket" class="vs-icon"></i></span>
              </span>
              <span class="">Save And Continue Button</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.saveandsubmit" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.saveandsubmit', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="save" class="vs-icon"></i></span>
              </span>
              <span class="">Save And Submit Button</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.saveandapprove" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.saveandapprove', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Save and Approve Button</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.view" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.view', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="eye" class="vs-icon"></i></span>
              </span>
              <span class="">Can View</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.edit" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.edit', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="edit" class="vs-icon"></i></span>
              </span>
              <span class="">Can Edit</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.delete" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.delete', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="trash" class="vs-icon"></i></span>
              </span>
              <span class="">Can Delete</span>
            </div></fieldset>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="auditForm.review" name="permissions[]" {{ isset($roleList) ? in_array('auditForm.review', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="book-open" class="vs-icon"></i></span>
              </span>
              <span class="">Can Review</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="audit.all_records" name="permissions[]" {{ isset($roleList) ? in_array('audit.all_records', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="circle" class="vs-icon"></i></span>
              </span>
              <span class="">See All Records</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
      </tbody>


      <thead>
        <tr>
          <th>Reports</th>
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
              <input type="checkbox" value="report.manage" name="permissions[]" {{ isset($roleList) ? in_array('report.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="file-text" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Reports</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.manage_assigned_resource" name="permissions[]" {{ isset($roleList) ? in_array('report.manage_assigned_resource', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="users" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Reports only to Schedule entry assigned as resource</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>

        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.view" name="permissions[]" {{ isset($roleList) ? in_array('report.view', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="eye" class="vs-icon"></i></span>
              </span>
              <span class="">Can View</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.edit" name="permissions[]" {{ isset($roleList) ? in_array('report.edit', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="edit" class="vs-icon"></i></span>
              </span>
              <span class="">Can Edit</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.editor" name="permissions[]" {{ isset($roleList) ? in_array('report.editor', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="layout" class="vs-icon"></i></span>
              </span>
              <span class="">Can Use Editor</span>
            </div></fieldset>
          </td>
        </tr>

        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.saveandcontinue" name="permissions[]" {{ isset($roleList) ? in_array('report.saveandcontinue', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="pocket" class="vs-icon"></i></span>
              </span>
              <span class="">Save and Continue Button</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.saveandsubmit" name="permissions[]" {{ isset($roleList) ? in_array('report.saveandsubmit', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Save and Submit Button</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.saveandapprove" name="permissions[]" {{ isset($roleList) ? in_array('report.saveandapprove', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="check-circle" class="vs-icon"></i></span>
              </span>
              <span class="">Save and Approve Button</span>
            </div></fieldset>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.saveandclose" name="permissions[]" {{ isset($roleList) ? in_array('report.saveandclose', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="x-square" class="vs-icon"></i></span>
              </span>
              <span class="">Save and Close</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.review" name="permissions[]" {{ isset($roleList) ? in_array('report.review', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="book-open" class="vs-icon"></i></span>
              </span>
              <span class="">Can Review</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
      </tbody>
      <!-- <thead>
        <tr>
          <th>Report Review Group</th>
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
              <input type="checkbox" value="report.group_1" name="permissions[]" {{ isset($roleList) ? in_array('report.group_1', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">{{ config('report.target_groups')['report.group_1'] }}</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.group_2" name="permissions[]" {{ isset($roleList) ? in_array('report.group_2', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">{{ config('report.target_groups')['report.group_2'] }}</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.group_3" name="permissions[]" {{ isset($roleList) ? in_array('report.group_3', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">{{ config('report.target_groups')['report.group_3'] }}</span>
            </div></fieldset>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.group_4" name="permissions[]" {{ isset($roleList) ? in_array('report.group_4', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">{{ config('report.target_groups')['report.group_4'] }}</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="report.group_5" name="permissions[]" {{ isset($roleList) ? in_array('report.group_5', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="user-plus" class="vs-icon"></i></span>
              </span>
              <span class="">{{ config('report.target_groups')['report.group_5'] }}</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody> -->
      <thead>
        <tr>
          <th>Settings</th>
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
              <input type="checkbox" value="settings.email.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.email.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="mail" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Email Settings</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.schedule.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.schedule.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="globe" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Schedule General Settings</span>
            </div></fieldset>
          </td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.audit.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.audit.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="folder" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Audit Settings</span>
            </div></fieldset>
          </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <fieldset><div class="vs-checkbox-con vs-checkbox-primary">
              <input type="checkbox" value="settings.standard.manage" name="permissions[]" {{ isset($roleList) ? in_array('settings.standard.manage', $roleList) ? 'checked' : '' : '' }}>
              <span class="vs-checkbox">
                <span class="vs-checkbox--check"><i data-feather="clipboard" class="vs-icon"></i></span>
              </span>
              <span class="">Manage Audit Standard Settings</span>
            </div></fieldset>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
