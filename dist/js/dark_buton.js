$(document).ready(function () {
    if(localStorage.getItem('darkMode') === 'enabled') {
      $('body').addClass('dark-mode');
      $('#themeSwitch').prop('checked', true);
    } else {
      $('body').removeClass('dark-mode');
      $('#themeSwitch').prop('checked', false);
    }
  
    $('#themeSwitch').change(function () {
      if ($(this).prop('checked')) {
        $('body').addClass('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
      } else {
        $('body').removeClass('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
      }
    });
  });


  $(document).ready(function() {
    // Mengecek apakah sidebar tertutup saat pertama kali dimuat
    if ($('body').hasClass('sidebar-mini')) {
      $('body').removeClass('sidebar-mini').addClass('sidebar-open');  // Pastikan sidebar terbuka saat pertama kali
    }

    // Toggle sidebar ketika ikon toggle diklik
    $('[data-widget="pushmenu"]').on('click', function() {
      $('body').toggleClass('sidebar-mini sidebar-open');
    });
  });