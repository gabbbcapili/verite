@inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg">
    <form action="{{ route('supplier.update', ['company' => $company]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit {{ $company->type == 'supplier' ? 'Supplier' : 'Client' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="form-body">
                  @include('app.user.company_details', ['company' => $company])
                  <div class="row">
                    @if($company->type == 'supplier')
                    <div class="col-lg-12 col-xs-12">
                      <div class="form-group">
                          <label for="name">Clients:</label>
                          @php
                            $companyClients = $company->clients->pluck('id')->toArray();
                          @endphp
                          <select class="form-control select2Modal" multiple="multiple" name="clients[]" id="clients">
                            @foreach($clients as $client)
                              <option value="{{ $client->id }}" {{ in_array($client->id, $companyClients) ? 'selected' : ''}}>{{ $client->companyDetails }}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                    @endif
                    @if($company->type == 'client')
                    <div class="col-lg-12 col-xs-12">
                      <div class="form-group">
                          <label for="name">Suppliers:</label>
                          @php
                            $companySuppliers = $company->suppliers->pluck('id')->toArray();
                          @endphp
                          <select class="form-control select2Modal" multiple="multiple" name="suppliers[]" id="suppliers">
                            @foreach($suppliers as $supplier)
                              <option value="{{ $supplier->id }}" {{ in_array($supplier->id, $companySuppliers) ? 'selected' : ''}}>{{ $supplier->companyDetails }}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
      </div>
    </div>
  </form>
</div>
<script src="{{ asset(mix('js/scripts/forms-validation/form-modal.js')) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $('.select2Modal').select2({
        dropdownParent: $("#view_modal")
      });
    });
</script>
