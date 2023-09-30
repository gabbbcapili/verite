<div class="row mb-2">
    <h4 class="card-title">Company Details</h4>
    <div class="col-lg-4 col-xs-12 mb-2">
        <div class="form-group">
            <label for="name">Company Name:</label>
            <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ isset($company) ? $company->company_name : '' }}">
        </div>
      </div>
      <div class="col-lg-4 col-xs-12">
        <div class="form-group">
            <label for="name">Acronym:</label>
            <input type="text" class="form-control" name="acronym" placeholder="Company Short Name" value="{{ isset($company) ? $company->acronym : '' }}">
        </div>
      </div>
      <div class="col-lg-4 col-xs-12">
            <div class="form-group">
                <label for="name">Website:</label>
                <input type="text" class="form-control" name="website" placeholder="Website" value="{{ isset($company) ? $company->website : '' }}">
            </div>
      </div>
      <div class="col-lg-4 col-xs-12">
        <div class="form-group">
            <label for="name">Contact Number:</label>
            <input type="text" class="form-control" name="contact_number" placeholder="Contact Number" value="{{ isset($company) ? $company->contact_number : '' }}">
        </div>
      </div>
    </div>
<div class="row mb-2">
    <div class="col-lg-4 col-xs-12">
        <div class="form-group">
            <label for="name">Logo: @if(isset($company)) Currently: <a href="{{ $company->ProfilePhotoUrl }}" target="_blank">logo.png</a> @endif</label>
            <input type="file" class="form-control" name="logo" placeholder="Logo">
        </div>
  </div>
  <div class="col-lg-8 col-xs-12">
    <div class="form-group">
        <label for="name">Address:</label>
        <input type="text" class="form-control" name="address" placeholder="Address" value="{{ isset($company) ? $company->address : '' }}">
    </div>
  </div>
</div>
