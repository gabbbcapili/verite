<label for="name">Contact Person:</label>
<select class="form-control select2" name="client_id" id="client">
    <option disabled selected></option>
    @foreach($contactPersons as $client)
        <option value="{{ $client->id }}">{{ $client->fullName }}</option>
    @endforeach
</select>
