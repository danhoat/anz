import $ from 'jquery';

export function recaptcha($form, $submit, sitekey) {
  $submit = $($submit);

  const $script_api = $('<script></script');
  $script_api.attr('src', '//www.google.com/recaptcha/api.js?render=' + sitekey);
  $('body').append($script_api);

  $submit.on('click', function(event) {
    event.preventDefault();

    grecaptcha.ready(function() {
      grecaptcha.execute(sitekey, { action: 'submit' })  // TODO: 集計に役立つように適切な action を設定する https://developers.google.com/recaptcha/docs/v3?hl=en#actions
        .then(function(token) {
          $form
            .append('<input type="hidden" name="__recaptcha_token" value="' + token + '">')
            .ready(function() { $form.trigger('submit'); });
        });
    });
  });
}
