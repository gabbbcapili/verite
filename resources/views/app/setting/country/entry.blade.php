<div class="col-lg-4 col-xs-12">
  <div class="form-group">
      <label for="name">Country:</label>
      <select class="form-control select2 selectFilter" name="country_id" id="country">
        <option disabled selected></option>
        @foreach($countries as $country)
          <option value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
      </select>
  </div>
</div>
<div class="col-lg-4 col-xs-12">
  <div class="form-group" id="fg_state">
    <label for="state">State:</label>
      <select class="form-control select2 selectFilter" name="state_id" id="state">
          <option disabled selected></option>
      </select>
  </div>
</div>