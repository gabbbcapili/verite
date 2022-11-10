$(document).on('click', '.confirmation', function(){
    Swal.fire({
      title: $(this).data('title'),
      showCancelButton: true,
      confirmButtonText: 'Yes',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url : $(this).data('action'),
          type : 'POST',
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
            if(result.reload) {
                location.reload();
            }
            if(result.table){
              $('#' + result.table).DataTable().ajax.reload();
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
            }
        },
        error: function(jqXhr, json, errorThrown){
          console.log(jqXhr);
          console.log(json);
          console.log(errorThrown);
        }
        });
      }
    })
});
