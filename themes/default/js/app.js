"use strict";

(function ($) {

  $('.load-average').tooltip({
    placement: 'top',
    trigger: 'hover'
  }).tooltip('hide');

  $('.popup-delete-user').click(function () {
    var username = $(this).data('user');
    $('#deleteUserName').val(username); // id input modal delete user add value="username"
    $('#target-delete-user').html(username);
  });

  $('#logout').click(function () {
    var logout_url = $(this).data('logouturl');
    var logout_message = $(this).data('logoutmessage');
    var protocol = window.location.protocol;
    var host = window.location.host;
    var uri = window.location.pathname;

    var url = protocol + '//' + host + uri;

    console.log(url);

    $.ajax({
      xhrFields: {
        withCredentials: true
      },
      headers: {
        'Authorization': 'Basic ' + btoa('logout:logout')
      },
      url: url
    });

    $.loader({
      id: 'jquery-loader',
      className: '',
      content: '<span>'+logout_message+'</span>',
      height: 60,
      width: 200,
      background: {id:'jquery-loader-background'}
    });

    setTimeout(function () {
      window.location.href = logout_url;
    }, 2000);
  });

})(jQuery);
