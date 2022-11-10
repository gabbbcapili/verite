$(".modal .form").submit(function(e) {
  e.preventDefault();
   $('.btn_save').prop('disabled', true);
    $.ajax({
      url : $(this).attr('action'),
      type : 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function(result){
        if(result.success == true){
          Swal.fire({
            icon: 'success',
            title: result.msg,
            showConfirmButton: false,
            timer: 1500,
            showClass: {
              popup: 'animate__animated animate__fadeIn'
            },
          });
          if(typeof(modalToggle) === 'undefined'){
            $('.view_modal').modal('toggle');
          }else{
            $('.' + modalToggle).modal('toggle');
          }
          if (result.select_id) {
            $('#'+result.select_id).append('<option value="'+result.option_id+'">'+result.option_name+'</option>').val(result.option_id).trigger('change');
          }
          else if(result.reload) {
            location.reload();
          }
        }else{
          if(result.msg){
            Swal.fire({
              icon: 'error',
              title: result.msg,
              showConfirmButton: false,
              timer: 1500,
              showClass: {
                popup: 'animate__animated animate__fadeIn'
              },
            });
          }
           $('.error').remove();
              $.each(result.error, function(index, val){
              $('[name="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
              });
        }
        $('.btn_save').prop('disabled', false);
         },
        error: function(jqXhr, json, errorThrown){
          console.log(jqXhr);
          console.log(json);
          console.log(errorThrown);
          $('.btn_save').prop('disabled', false);
        }
    });
});



