<div class="row mb-2">
    <h4 class="card-title">Company Details</h4>
    <div class="col-lg-4 col-xs-12">
        <div class="form-group">
            <label for="name">Company Name:</label>
            <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ isset($user) ? $user->company_name : '' }}">
        </div>
      </div>
      <div class="col-lg-4 col-xs-12">
            <div class="form-group">
                <label for="name">Website:</label>
                <input type="text" class="form-control" name="website" placeholder="Website" value="{{ isset($user) ? $user->website : '' }}">
            </div>
      </div>
      <div class="col-lg-4 col-xs-12">
        <div class="form-group">
            <label for="name">Contact Number:</label>
            <input type="text" class="form-control" name="contact_number" placeholder="Contact Number" value="{{ isset($user) ? $user->contact_number : '' }}">
        </div>
      </div>
    </div>
<div class="row mb-2">
  <div class="col-lg-8 col-xs-12">
    <div class="form-group">
        <label for="name">Address:</label>
        <input type="text" class="form-control" name="address" placeholder="Address" value="{{ isset($user) ? $user->address : '' }}">
    </div>
  </div>
</div>
<hr>
