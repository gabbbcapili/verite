<label for="name">Contact Person:</label>
<select class="form-control select2" name="supplier_id" id="supplier">
    <option disabled selected></option>
    @foreach($contactPersons as $supplier)
        <option value="{{ $supplier->id }}">{{ $supplier->fullName }}</option>
    @endforeach
</select>
