
$(function () {
  'use strict';
  var dt_basic_table = $('#' + table_id);
  if (typeof(order) === '1ined') {
    var order = [];
  }

  if (dt_basic_table.length) {
    var dt_basic = dt_basic_table.DataTable({
      processing: true,
      serverSide: true,
      "scrollX": true,
      ajax: table_route,
      "aaSorting": [],
      order: order,
      columns: columnns,
      // dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"li><"col-sm-12 col-md-6"p>>',
      dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"li><"col-sm-12 col-md-6"p>>',
      displayLength: typeof(displayLength) === 'undefined' ? 7 : displayLength,
      lengthMenu: typeof(lengthMenu) === 'undefined' ? [7, 10, 25, 50, 75, 100] : lengthMenu,
      buttons: typeof(buttons) === 'undefined' ? [] : buttons,
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
      "drawCallback": typeof(drawCallback) === 'undefined' ? function(){} : drawCallback,
    });
    // $('div.head-label').html('<h6 class="mb-0"> ' + table_title +' </h6>');
  }



  $('.view_modal').on('hidden.bs.modal', function () {
        dt_basic.ajax.reload();
  });

  $('.show_modal').on('hidden.bs.modal', function () {
        dt_basic.ajax.reload();
  });

  $(document).on('change', '.selectFilter', function() {
        dt_basic.ajax.reload();
      });
});
