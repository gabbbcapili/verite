$(document).on('click', '.addQuestionTableRow', function(){
    var row = parseInt($(this).data('count'));
    $(this).data('count', $(this).data('count') + 1);
    var $tr = '';

    $tr += '<tr>';
    $tr += '<td><div class="d-flex justify-content-end"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-success deleteQuestionRow" data-bs-toggle-modal="tooltip" title="Delete"><i data-feather="delete"></i></button></div></div></td>';
    for(let i=0; i < $(this).data('columns-count'); i++){
        $tr += '<td><input class="form-control" type="text" name="table['+ $(this).data('table-id') +']['+ row +']['+ i +']"></td>';
    }
    $tr += '</tr>';
    $('table[id="'+ $(this).data('table') +'"] tr:last').after($tr);
    feather.replace({
      width: 14,height: 14
    });
});

$(document).on('click', '.deleteQuestionRow', function(){
    $('[data-bs-toggle="tooltip"]').tooltip('hide')
    $(this).closest('tr').remove();
});
