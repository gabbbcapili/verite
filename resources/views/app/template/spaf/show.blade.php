@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        View Template {{ $template->name }}
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <div class="row">
            <!-- show question -->
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                          <h4 class="card-title text-center">Template Preview</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row" id="template_preview">
                                    @include('app.template.spaf.preview', ['template' => $template])
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <!-- show question -->
        </div>
    </div>
    <div class="modal-footer">

    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      $('input[name="permissions[]"]').attr('disabled', 'disabled');
    });
</script>
