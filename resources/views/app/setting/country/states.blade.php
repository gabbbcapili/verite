<label for="states">State:</label>
<select class="form-control select2" name="state_id" id="state">
    <option disabled selected></option>
    @foreach($states as $state)
        <option value="{{ $state->id }}">{{ $state->name }}</option>
    @endforeach
</select>
