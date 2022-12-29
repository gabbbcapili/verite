<label for="name">Supplier:</label>
<select class="form-control select2" name="supplier_company_id" id="supplier_company">
    <option disabled selected></option>
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->id }}">{{ $supplier->companyDetails }}</option>
    @endforeach
</select>
