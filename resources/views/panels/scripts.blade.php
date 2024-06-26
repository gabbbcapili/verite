<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset(mix('vendors/js/ui/jquery.sticky.js'))}}"></script>
<script type="text/javascript">
  function promptConfirmationBeforeUnload(e){
      // Cancel the event
      e.preventDefault();
      // Chrome requires returnValue to be set
      e.returnValue = '';
    }
</script>
@yield('vendor-script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js" integrity="sha512-hMjDyb/4G3SapFEM71rK+Gea0+ZEr9vDlhBTyjSmRjuEgza0Ytsb67GE0aSpRMYW++z6kZPPcnddwlUG6VKm9w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>

<!-- custome scripts file for user -->
<script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

@if($configData['blankPage'] === false)
<script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
@endif

<!-- Global Page Scripts -->
<script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('vendors/js/extensions/polyfill.min.js') }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<!-- Global Page Scripts -->
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

@stack('modals')
@livewireScripts
<script defer src="{{ asset(mix('vendors/js/alpinejs/alpine.js')) }}"></script>
<script src="{{ asset('js/scripts/print/printThis.js') }}"></script>
<script type="text/javascript">
  $(document).on('click', '.modal_button', function() {
      $.ajax({
          url: $(this).data('action'),
          method: "GET",
          success:function(result)
          {
            $('#view_modal').html(result);
              if($('#view_modal').is(':visible')){
              }else{
                $('#view_modal').modal({backdrop: 'static', keyboard: false}).modal('toggle');
              }
              if (feather) {
                feather.replace({
                  width: 14, height: 14
                });
              }
          }
      });
  });

  function separateDates(dateString) {
      // Split the string by " to " to separate the two dates
      var dates = dateString.split(" to ");
      
      // If only one date is provided, set it as both start and end dates
      if (dates.length === 1) {
          return {
              startDate: dates[0].trim(),
              endDate: dates[0].trim()
          };
      }
      
      // Trim any leading or trailing spaces from the dates
      var startDate = dates[0].trim();
      var endDate = dates[1].trim();
      
      return {
          startDate: startDate,
          endDate: endDate
      };
  }

  function scheduleGetAllowedDates(){
    var allowed = $('#dateRange').val();
    var allowedDates = separateDates(allowed);
    return allowedDates;
  }

  $(document).on('click', '.hrefButton', function() {
    // window.open($(this).data('action'), '_blank');
    window.location.href = $(this).data('action');
  });


  $(document).on('click', '.confirmWithNotes', function(){
    Swal.fire({
      title:$(this).data('title'),
      text: $(this).data('text'),
      input: 'text',
      inputValidator: (value) => {
        return !value && 'This field is required.'
      },
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText:$(this).data('confirmbutton'),
      }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
              url: $(this).data('action'),
              method: "POST",
              data:{
                notes : result.value,
              },
              success:function(result)
              {
                if(result.success){
                  Swal.fire({
                    icon: 'success',
                    title: result.msg,
                    showConfirmButton: false,
                    timer: 1500,
                    showClass: {
                      popup: 'animate__animated animate__fadeIn'
                    },
                  });
                  $(".view_modal").trigger("hidden.bs.modal");
                  if(result.redirect){
                    setTimeout(function(){
                          window.location.replace(result.redirect);
                      }, 1500);
                  }
                }else{
                  Swal.fire({
                    icon: 'error',
                    title: result.msg,
                    showClass: {
                      popup: 'animate__animated animate__fadeIn'
                    },
                  });
                }
              }
          });
      }
    })
  });

  $(document).on('click', '.confirm', function(){
    Swal.fire({
        title:$(this).data('title'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              url: $(this).data('action'),
              method: "POST",
              success:function(result)
              {
                Swal.fire({
                  icon: 'success',
                  title: result.msg,
                  showConfirmButton: false,
                  timer: 1500,
                  showClass: {
                    popup: 'animate__animated animate__fadeIn'
                  },
                });
                $(".view_modal").trigger("hidden.bs.modal");
                if(result.redirect){
                  setTimeout(function(){
                        window.location.replace(result.redirect);
                    }, 1500);
                }
              }
          });
        }
      })
  });

  $(document).on('click', '.confirmDelete', function(){
    Swal.fire({
        title:$(this).data('title'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              url: $(this).data('action'),
              method: "DELETE",
              success:function(result)
              {
                Swal.fire({
                  icon: 'success',
                  title: result.msg,
                  showConfirmButton: false,
                  timer: 1500,
                  showClass: {
                    popup: 'animate__animated animate__fadeIn'
                  },
                });
                $(".view_modal").trigger("hidden.bs.modal");

                if($('#view_modal').hasClass('show')){
                  $('#view_modal').modal('toggle');
                }else{
                  if(result.redirect){
                    setTimeout(function(){
                          window.location.replace(result.redirect);
                      }, 1500);
                  }
                }
                            
                if (result.removeRow) {
                    $('#'+ result.removeRow).remove();
                }
              }
          });
        }
      })
  });


  $(document).on('click', '.modal_button_overlap', function() {
      $.ajax({
          url: $(this).data('action'),
          method: "GET",
          success:function(result)
          {
            $('#show_modal').html(result);
            $('#show_modal').modal({backdrop: 'static', keyboard: false}).modal('toggle');
              if (feather) {
                feather.replace({
                  width: 14, height: 14
                });
              }
          }
      });
  });

  $(document).on('hidden.bs.modal', '#show_modal', function () {
      $('#show_modal').empty();
      $('#view_modal').empty();
  });



  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready(function(){
    $.ajax({
          url: "{{ route('getBadges') }}",
          method: "GET",
          success:function(result)
          {
            $.each(result.data, function (index, item) {
              if(item != 0){
                $('#' + index).text(item);
              }
            });

          }
      });

    // $(document).on('mouseup', "input.form-check-input:radio", function(e){
    //   var before = $(this).val();
    //   console.log(before);

    // }).change(function(before){
    //   var after = $(this).val();
    //   console.log(after);

    // });

    $("input.form-check-input:radio").click(function(){
        var $self = $(this);
        var $inputToUpdate = $('input.radioButtonInput:hidden[name="'+ $self.data('input') +'"]');
        if ($self.attr('checkstate') == 'true')
        {
            $self.prop('checked', false);
            $inputToUpdate.val('');
            $self.each( function() {
                $self.attr('checkstate', 'false');
            })
        }
        else
        {
            $("input.form-check-input:radio").attr('checkstate', 'false');
            $self.prop('checked', true);
            $self.attr('checkstate', 'true');
            $inputToUpdate.val($self.val());
        }
  });

    $(document).on('change', '#country', function(){
      var url = '{{ route("country.loadStates", ":id") }}';
                  url = url.replace(':id', $(this).val());
      $.ajax({
          url: url,
          method: "POST",
          success:function(result)
          {
            $('#fg_state').html(result);
            $('#state').select2();
          }
      });
    });
  });
</script>
