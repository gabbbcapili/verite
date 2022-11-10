@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-xl">
    <form action="{{ route('template.group.store', ['template' => $template]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add Question
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-6">
                    <label class="form-label">Header:</label>
                    <input type="text" name="header" placeholder="Header" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="d-flex justify-content-end mb-1">
                    <button class="btn btn-primary" type="button" id="add_row_question"><i data-feather="plus-circle"></i> Add Question</button>
                  </div>
                  <div class="table-responsive">
                    <input type="hidden" id="question_row_count" value="1">
                    <table class="table table-striped" id="question_table">
                      <thead>
                        <tr>
                          <th style="width: 25%;">Question</th>
                          <th style="width: 20%;">Type</th>
                          <th style="width: 10%;">Required <i data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Check / Uncheck this field wether this field is required to fill up or not."></i></th>
                          <th style="width: 10%;">Next Line <i data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Uncheck this field if it is a multiple question in one line."></i></th>
                          <th style="width: 25%;">For Checkbox/Radio <i data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="If the field is checkbox or radio, put all the options in this field separeted by comma (,) e.g. 'Yes,No' 'Management,Direct,Outsorce,Dispatch'"></i></th>
                          <th style="width: 10%;">Action</th>
                        </tr>
                      </thead>
                      <tbody id="question_sortable">
                        <tr>
                          <td>
                            <textarea name="question[1][text]" id="question.1.text" class="form-control"></textarea>
                          </td>
                          <td>
                            <select class="form-control selectType" name="question[1][type]" id="question.1.type">
                              <option value="input">Text</option>
                              <option value="checkbox">Check Box</option>
                              <option value="radio">Radio Button</option>
                            </select>
                          </td>
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="1" name="question[1][required]" id="question.1.required" checked>
                              <label class="form-check-label">
                                Required
                              </label>
                            </div>
                          </td>
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="1" name="question[1][next_line]" id="question.1.next_line" checked>
                              <label class="form-check-label">
                                Next Line
                              </label>
                            </div>
                          </td>
                          <td>
                            <input type="textbox" name="question[1][for_checkbox]" id="question.1.for_checkbox" class="form-control for_checkbox" placeholder="Management,Direct,Outsorce,Dispatch">
                          </td>
                          <td>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle="tooltip" title="Delete"><i data-feather="delete"></i></button>
                                    <span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon" data-bs-toggle="tooltip" title="Move"><i class="" data-feather="move"></i></span>
                                </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
      $('.selectType').each(function(i, obj) {
        if($(this).find('option:selected').val() != 'input'){
          $(this).closest('tr').find('.for_checkbox').removeAttr('disabled');
        }else{
          $(this).closest('tr').find('.for_checkbox').attr('disabled', 'disabled');
        }
      });
      $(document).on('change', '.selectType', function(){
        if($(this).find('option:selected').val() != 'input'){
          $(this).closest('tr').find('.for_checkbox').removeAttr('disabled');
        }else{
          $(this).closest('tr').find('.for_checkbox').attr('disabled', 'disabled');
        }
      });
      $('[data-bs-toggle="tooltip"]').tooltip();
      $("#question_sortable").sortable({
        handle: ".ui-icon",
        items: "tr",
      });

      $('#add_row_question').click(function(){
        var row = parseInt($('#question_row_count').val()) + 1;
        $('#question_row_count').val(row);
        var $tr = '';

        $tr += '<tr>';
        $tr += '<td><textarea name="question['+ row +'][text]" id="question.'+ row +'.text" class="form-control"></textarea></td>';
        $tr += '<td><select class="form-control selectType" name="question['+ row +'][type]" id="question.'+ row +'.type"><option value="input">Text</option><option value="checkbox">Check Box</option><option value="radio">Radio Button</option></select></td>';
        $tr += '<td><div class="form-check"><input class="form-check-input" type="checkbox" value="1" name="question['+ row +'][required]" id="question.'+ row +'.required" checked><label class="form-check-label">Required</label></div></td>';
        $tr += '<td><div class="form-check"><input class="form-check-input" type="checkbox" value="1" name="question['+ row +'][next_line]" id="question.'+ row +'.next_line" checked><label class="form-check-label">Next Line</label></div></td>';
        $tr += '<td><input type="textbox" name="question['+ row +'][for_checkbox]" id="question.'+ row +'.for_checkbox" class="form-control for_checkbox" placeholder="Management,Direct,Outsorce,Dispatch" disabled></td>';
        $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle="tooltip" title="Delete"><i data-feather="delete"></i></button><span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon" data-bs-toggle="tooltip" title="Move"><i class="" data-feather="move"></i></span></div></div></td>';
        $tr += '</tr>';
        $('#question_table tr:last').after($tr);
        feather.replace({
          width: 14,height: 14
        });
        $('[data-bs-toggle="tooltip"]').tooltip();
      });

      $(document).on('click', '.delete_row', function(){
        $('[data-bs-toggle="tooltip"]').tooltip('hide')
        $(this).closest('tr').remove();
      });
    });
</script>