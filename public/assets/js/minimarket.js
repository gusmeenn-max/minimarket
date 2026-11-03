(function ($) {
  'use strict';

  // Sidebar (mobile)
  $(document).on('click', '[data-sidebar-toggle]', function () { $('body').toggleClass('sidebar-open'); });
  $(document).on('click', '.sidebar-backdrop', function () { $('body').removeClass('sidebar-open'); });

  // Bahasa Indonesia untuk DataTables
  window.mmDataTableLang = {
    search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
    infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_ data)', zeroRecords: 'Data tidak ditemukan',
    emptyTable: 'Belum ada data',
    paginate: { first: 'Awal', last: 'Akhir', next: '›', previous: '‹' }
  };

  $(function () {
    if ($.fn.DataTable) {
      $('table.js-datatable').each(function () {
        var $t = $(this);
        $t.DataTable({
          language: window.mmDataTableLang,
          pageLength: parseInt($t.data('page-length')) || 10,
          order: [],
          columnDefs: [{ orderable: false, targets: 'no-sort' }]
        });
      });
    }
    // Alert otomatis hilang
    setTimeout(function () { $('.alert[data-autodismiss]').each(function () { bootstrap.Alert.getOrCreateInstance(this).close(); }); }, 5000);

    // Konfirmasi hapus
    $(document).on('submit', 'form[data-confirm]', function (e) {
      if (!window.confirm($(this).data('confirm'))) e.preventDefault();
    });

    // Tampilkan / sembunyikan password
    $(document).on('click', '[data-toggle-password]', function () {
      var $i = $($(this).data('toggle-password'));
      var show = $i.attr('type') === 'password';
      $i.attr('type', show ? 'text' : 'password');
      $(this).find('i').toggleClass('bi-eye', !show).toggleClass('bi-eye-slash', show);
    });
  });
})(jQuery);
