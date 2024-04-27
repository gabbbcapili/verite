$(function() {
  var button = 'save';
  $('input[type="submit"]').on('click', function(){
       button = this.name;
  });
  $(".form").submit(function(e) {
    tinyMCE.triggerSave();
    e.preventDefault();
    tinyMCE.triggerSave();
    if($('.btn_save').prop('disabled') == true){
      return false;
    }
     $('.btn_save').prop('disabled', true);
      $.ajax({
        url : $(this).attr('action'),
        type : 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(result){
          if(result.success == true){
            if(result.msg){
                Swal.fire({
                icon: 'success',
                title: result.msg,
                showConfirmButton: false,
                timer: 1500,
                showClass: {
                  popup: 'animate__animated animate__fadeIn'
                },
              });
                $('.error').remove();
                if(button != 'no_action'){
                  $('.form')[0].reset();
                }

            }

          // $(".select").val(null).trigger("change");
          if(button == 'save_with_reload_table'){
            $('.form')[0].reset();
            $('#' + table_id).DataTable().ajax.reload();
          }else if(button == 'save'){
            if (typeof promptConfirmationBeforeUnload === 'function') {
              console.log('beforeUnload Function Exists');
              window.removeEventListener('beforeunload', promptConfirmationBeforeUnload);
            }
            setTimeout(function(){
                window.location.replace(result.redirect);
            }, 1500);
          }else if(button == 'no_action'){

          }else{
            $('.form')[0].reset();
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
            var errorCounter = 1;
              $.each(result.error, function(index, val){
                var elem = $('[name="'+ index +'"]');
                // console.log(elem.attr('name'));
                var type = elem.attr('type');
                if(errorCounter == 1){
                  if(type == undefined){
                    scrollElem = $('[id="'+ index +'"]');
                    if(scrollElem.attr('type') == 'hidden'){
                      scrollElem = scrollElem.parent();
                    }
                  }else{
                    scrollElem = elem;
                  }
                  if(scrollElem){
                    window.scrollTo({top: $(scrollElem).offset().top - 100, behavior: 'smooth'});
                  }
                }
                errorCounter += 1;
                if(type == 'radio'){
                  elem.after('<label class="text-danger error">' + val + '</label>');
                }else if(type == 'hidden'){
                  elem.first().after('<label class="text-danger error">' + val + '</label>');
                }else{
                    elem.after('<label class="text-danger error">' + val + '</label>');
                    $('[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
                }

              });
          }
          $('.btn_save').prop('disabled', false);
        },
        error: function(jqXhr, json, errorThrown){
          console.log(jqXhr);
          console.log(json);
          console.log(errorThrown);
          if(json){
            if(! navigator.onLine){
              Swal.fire({
                icon: 'error',
                title: 'No internet connection. Please try again later.',
                // showConfirmButton: false,
                showClass: {
                  popup: 'animate__animated animate__fadeIn'
                },
              });
            }else{
              Swal.fire({
                icon: 'error',
                title: 'An error has occurred. Please try again later.',
                // showConfirmButton: false,
                showClass: {
                  popup: 'animate__animated animate__fadeIn'
                },
              });
            }
          }
          $('.btn_save').prop('disabled', false);
        }
      });
  });
});
