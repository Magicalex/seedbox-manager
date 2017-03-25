"use strict";

(function ($) {
  $('.load-average').tooltip({
    placement: 'top',
    trigger: 'hover'
  }).tooltip('hide');

  $('.popup-delete-user').click(function () {
    var username = $(this).data('user');
    $('#deleteUserName').val(username);
    $('#target-delete-user').html(username);
  });

  $('#logout').click(function () {
    var logout_url = $(this).data('logouturl');
    var logout_message = $(this).data('logoutmessage');
    var protocol = window.location.protocol;
    var host = window.location.host;
    var uri = window.location.pathname;

    $.ajax({
      xhrFields: {
        withCredentials: true
      },
      headers: {
        'Authorization': 'Basic ' + btoa('logout:logout')
      },
      url: protocol + '//' + host + uri
    });

    $.loader({
      id: 'jquery-loader',
      className: '',
      content: '<p><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p><p>'+logout_message+'</p>',
      height: 60,
      width: 200,
      background: {id:'jquery-loader-background'}
    });

    setTimeout(function () {
      window.location.href = logout_url;
    }, 2000);
  });
})(jQuery);
