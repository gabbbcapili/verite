@inject('request', 'Illuminate\Http\Request')

<div class="modal-dialog modal-xl">
    <form action="{{ route('template.group.update', ['group' => $group]) }}" method="POST" class="form" enctype='multipart/form-data'>
      @method('put')
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit Group
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                @if(! in_array($group->template->type, App\Models\Template::$forReport))
                <div class="row mb-2">
                  <div class="col-6">
                    <label class="form-label">Header:</label>
                    <input type="text" name="header" placeholder="Header" class="form-control" value="{{ $group->header }}">
                  </div>
                  <div class="col-3 mt-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="displayed_on_schedule" id="displayedOnSchedule" value="1" {{ $group->displayed_on_schedule ? 'checked' : '' }}/>
                      <label class="form-check-label" for="displayedOnSchedule">Displayed on Schedule</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="d-flex justify-content-end mb-1">
                    <button class="btn btn-primary add_row_question" type="button"><i data-feather="plus-circle"></i> Add Question</button>
                  </div>
                  <div class="table-responsive">
                    <input type="hidden" id="question_row_count" value="{{ $group->questions->count() }}">
                    <table class="table table-striped" id="question_table">
                      <thead>
                        <tr>
                          <th style="width: 25%;">Question</th>
                          <th style="width: 20%;">Type</th>
                          <th style="width: 10%;">Required <i data-feather="info" data-bs-toggle-modal="tooltip" data-bs-placement="top" title="Check / Uncheck this field whether this field is required to fill up or not."></i></th>
                          <th style="width: 10%;">Next Line <i data-feather="info" data-bs-toggle-modal="tooltip" data-bs-placement="top" title="Uncheck if multiple questions in one line."></i></th>
                          <th style="width: 25%;">For Checkbox/Radio/Table <i data-feather="info" data-bs-toggle-modal="tooltip" data-bs-placement="top" title="If the field is checkbox or radio or table, put all the options/columns in this field separated by vertical bar ( | ) e.g. 'Yes|No' 'Management|Direct|Outsource|Dispatch, Column1|Column2|Column3'."></i></th>
                          @if(in_array($group->template->type, App\Models\Template::$forAudit))
                          <th style="width: 15%">Standards</th>
                          @endif
                          <th style="width: 10%;">Action</th>
                        </tr>
                      </thead>
                      <tbody id="question_sortable">
                        @foreach($group->questions as $q)
                          <input type="hidden" name="question_id" value="{{ $q->id }}">
                          <tr>
                            <td>
                              <textarea name="question[{{ $loop->iteration }}][text]" id="question.{{ $loop->iteration }}.text" class="form-control">{{ $q->text }}</textarea>
                            </td>
                            <td>
                              <select class="form-control selectType" name="question[{{ $loop->iteration }}][type]" id="question.{{ $loop->iteration }}.type">
                                <option value="input" {{ $q->type == 'input' ? 'selected' : '' }}>Text</option>
                                <option value="checkbox" {{ $q->type == 'checkbox' ? 'selected' : '' }}>Check Box</option>
                                <option value="radio" {{ $q->type == 'radio' ? 'selected' : '' }}>Radio Button</option>
                                <option value="title" {{ $q->type == 'title' ? 'selected' : '' }}>Title</option>
                                <option value="email" {{ $q->type == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="number" {{ $q->type == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="textarea" {{ $q->type == 'textarea' ? 'selected' : '' }}>Long Text</option>
                                <option value="file" {{ $q->type == 'file' ? 'selected' : '' }}>File</option>
                                <option value="file_multiple" {{ $q->type == 'file_multiple' ? 'selected' : '' }}>Multiple File</option>
                                <option value="table" {{ $q->type == 'table' ? 'selected' : '' }}>Table</option>
                              </select>
                            </td>
                            <td>
                            <div class="form-check">
                              <input class="form-check-input for_required" type="checkbox" value="1" name="question[{{ $loop->iteration }}][required]" id="question.{{ $loop->iteration }}.required" {{ $q->required ? 'checked' : '' }}>
                              <label class="form-check-label">
                                Required
                              </label>
                            </div>
                          </td>
                            <td>
                              <div class="form-check">
                                <input class="form-check-input for_next_line" type="checkbox" value="1" name="question[{{ $loop->iteration }}][next_line]" id="question.{{ $loop->iteration }}.next_line" {{ $q->next_line ? 'checked' : '' }}>
                                <label class="form-check-label">
                                  Next Line
                                </label>
                              </div>
                            </td>
                            <td>
                              <textarea name="question[{{ $loop->iteration }}][for_checkbox]" id="question.{{ $loop->iteration }}.for_checkbox" class="form-control for_checkbox" placeholder="Management|Direct|Outsource|Dispatch">{{ $q->for_checkbox }}</textarea>
                            </td>
                            @if(in_array($group->template->type, App\Models\Template::$forAudit))
                            <td>
                              <select class="form-control select2Modal" multiple="multiple" name="question[{{ $loop->iteration }}][standards][]" id="question.{{ $loop->iteration }}.standards">
                                @foreach($standards as $standard)
                                  <option value="{{ $standard->id }}" {{ in_array($standard->id, explode(',', $q->standards)) ? 'selected' : '' }}>{{ $standard->nameTruncated }}</option>
                                @endforeach
                              </select>
                            </td>
                            @endif
                            <td>
                              <div class="d-flex justify-content-end">
                                  <div class="btn-group" role="group">
                                      <button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button>
                                      <!-- <button type="button" class="btn btn-sm btn-outline-success duplicate_row" data-bs-toggle-modal="tooltip" title="Copy"><i data-feather="copy"></i></button> -->
                                      <button type="button" class="btn btn-sm btn-outline-success add_row_question_bottom" data-bs-toggle-modal="tooltip" title="Add Question"><i data-feather="plus-circle"></i></button>
                                      <span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon" data-bs-toggle-modal="tooltip" title="Move"><i class="" data-feather="move"></i></span>
                                  </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @else
              <input type="hidden" name="header" placeholder="Header" class="form-control" value="{{ $group->header }}">
              <div class="row mb-2 justify-content-center align-items-center">
                <div class="col-7">
                  <input type="hidden" name="question[0][question_id]" value="{{ $group->questions->first()->id }}">
                  <input type="hidden" name="question[0][type]" value="editor">
                  <textarea class="form-control tinymce" name="question[0][text]">{{ $group->questions->first()->text }}</textarea>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        @if(! in_array($group->template->type, App\Models\Template::$forReport))
        <button class="btn btn-primary add_row_question" type="button"><i data-feather="plus-circle"></i> Add Question</button>
        @endif
          <button type="submit" class="btn btn-primary no-print btn_save"><i data-feather="save"></i> Save
          </button>
      </div>
    </div>
  </form>
</div>
<script src="{{ asset(mix('js/scripts/forms-validation/form-modal.js')) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
      tinymce.init({
        selector: ".tinymce",
        plugins: 'pagebreak image code fullscreen table lists',
        height : "700"
      });
      $('.trumbowyg').trumbowyg({
        plugins: {
            fontsize: {
                sizeList: [
                    '14px',
                    '16px',
                    '18px',
                    '20px',
                    '22px',
                    '24px',
                    '30px',
                    '34px',
                    '40px',
                ],
                allowCustomSize: false
            }
        },
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['fontsize'],
            ['foreColor', 'backColor'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['emoji'],
        ]
      });

      $('.select2Modal').select2({
        dropdownParent: $("#modal-body")
      });
      var standards = @json($standards);
      var standardsSelection = '';
      $.each(standards, function(k, u) {
        standardsSelection += '<option value="'+ u.id +'">'+ u.name +'</option>';
      });

      function changeType(select, initial = false){
        if(select.find('option:selected').val() == 'checkbox' || select.find('option:selected').val() == 'radio' || select.find('option:selected').val() == 'table'){
          select.closest('tr').find('.for_checkbox').removeAttr('disabled');
        }else{
          select.closest('tr').find('.for_checkbox').attr('disabled', 'disabled');
        }
        if(select.find('option:selected').val() == 'title'){
          select.closest('tr').find('.for_required').attr('disabled', 'disabled');
          select.closest('tr').find('.for_required').prop('checked', false);
          select.closest('tr').find('.for_next_line').attr('disabled', 'disabled');
          select.closest('tr').find('.for_next_line').prop('checked', true);
        }else{
          if(! initial){
            select.closest('tr').find('.for_required').removeAttr('disabled');
            select.closest('tr').find('.for_required').prop('checked', true);
            select.closest('tr').find('.for_next_line').removeAttr('disabled');
            select.closest('tr').find('.for_next_line').prop('checked', true);
          }
        }
      }
      $('.selectType').each(function(i, obj) {
        changeType($(this), true);
      });
      $(document).on('change', '.selectType', function(){
        changeType($(this), false);
      });
      $('[data-bs-toggle-modal="tooltip"]').tooltip({
        container : 'body',
        trigger: 'hover',
      });
      $("#question_sortable").sortable({
        handle: ".ui-icon",
        items: "tr",
      });

      function addQuestion(target){
        var row = parseInt($('#question_row_count').val()) + 1;
        $('#question_row_count').val(row);
        var $tr = '';

        $tr += '<tr>';
        $tr += '<td><textarea name="question['+ row +'][text]" id="question.'+ row +'.text" class="form-control"></textarea></td>';
        $tr += '<td><select class="form-control selectType" name="question['+ row +'][type]" id="question.'+ row +'.type"><option value="input">Text</option><option value="checkbox">Check Box</option><option value="radio">Radio Button</option><option value="title">Title</option><option value="email">Email</option><option value="number">Number</option><option value="textarea">Long Text</option><option value="file">File</option><option value="file_multiple">Multiple File</option><option value="table">Table</option></select></td>';
        $tr += '<td><div class="form-check"><input class="form-check-input for_required" type="checkbox" value="1" name="question['+ row +'][required]" id="question.'+ row +'.required"><label class="form-check-label">Required</label></div></td>';
        $tr += '<td><div class="form-check"><input class="form-check-input for_next_line" type="checkbox" value="1" name="question['+ row +'][next_line]" id="question.'+ row +'.next_line" checked><label class="form-check-label">Next Line</label></div></td>';
        $tr += '<td><textarea name="question['+ row +'][for_checkbox]" id="question.'+ row +'.for_checkbox" class="form-control for_checkbox" placeholder="Management|Direct|Outsource|Dispatch" disabled></textarea></td>';
        @if(in_array($group->template->type, App\Models\Template::$forAudit))
        $tr += '<td><select class="form-control select2Modal" multiple="multiple" name="question['+ row +'][standards][]" id="question.'+ row +'.standards">'+ standardsSelection +'</select></td>';
        @endif
        $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success delete_row" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button><button type="button" class="btn btn-sm btn-outline-success add_row_question_bottom" data-bs-toggle-modal="tooltip" title="Add Question"><i data-feather="plus-circle"></i></button><span role="button" class="btn btn-sm btn-outline-success cursor-move ui-icon" data-bs-toggle-modal="tooltip" title="Move"><i class="" data-feather="move"></i></span></div></div></td>';
        $tr += '</tr>';
        target.after($tr);
        feather.replace({
          width: 14,height: 14
        });
        $('[data-bs-toggle-modal="tooltip"]').tooltip({
          container : 'body',
          trigger: 'hover',
        });
        @if(in_array($group->template->type, App\Models\Template::$forAudit))
          $('.select2Modal').select2({
            dropdownParent: $("#modal-body")
          });
        @endif
      }

      $('.add_row_question').click(function(){
        addQuestion($('#question_table tr:last'));
      });

      $('.add_row_question_bottom').click(function(){
        addQuestion($(this).closest('tr'));
      });

      // $(document).on('click', '.add_row_question', function(){
      //   addQuestion($('#question_table tr:last'));
      // });

      // $(document).on('click', '.add_row_question_bottom', function(){
      //   addQuestion($(this).closest('tr'));
      // });


      $(document).on('click', '.delete_row', function(){
        $('[data-bs-toggle-modal="tooltip"]').tooltip('hide')
        $(this).closest('tr').remove();
      });
      $(document).on('click', '.duplicate_row', function(){
        var tr = $(this).closest('tr');
        tr.after(tr.clone());
      });
    });
</script>
