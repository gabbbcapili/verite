<label for="name">Supplier:</label>
<select class="form-control select2" name="supplier_id" id="supplier">
    <option disabled selected></option>
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->id }}">{{ $supplier->fullName }}</option>
    @endforeach
</select>
