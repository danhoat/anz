import $ from 'jquery';
import 'jquery-ui/ui/widgets/datepicker';
import 'jquery-ui/ui/i18n/datepicker-ja';
import 'jquery-validation';
import 'jquery.autokana';
import './zip2addr';
import { is_email, is_tel, is_zip, is_address, require_from_group, match_pattern } from './methods';
import { messages } from './messages';
import { update_submit_disabled } from './update_submit_disabled';
import { update_num_remaining } from './update_num_remaining';
import { recaptcha } from './recaptcha';

$.validator.addMethod('email', is_email);
$.validator.addMethod('tel', is_tel);
$.validator.addMethod('zip', is_zip);
$.validator.addMethod('address', is_address);
$.validator.addMethod('require_from_group', require_from_group);
$.validator.addMethod('pattern', match_pattern);

$.extend($.validator.messages, messages);

$.fn.qms3_form = function(options) {
  // 適切なタグに対してスクリプトが起動しているかチェックする

  if (!this.get(0)) {
    console.error('⚠️ qms3_form() の起動に失敗しました\n\t対象タグが選択されていません');
    console.error('⚠️ qms3_form() は必ず <form> タグに対して実行してください\n\t起動スクリプトのセレクターに間違いがないか確認してください');
    return;
  }

  const tag_name = this.get(0).tagName.toLowerCase();
  if (tag_name != 'form') {
    console.error(`⚠️ <${tag_name}> に対して qms3_form() を実行しようとしています`);
    console.error('⚠️ qms3_form() は必ず <form> タグに対して実行してください\n\t起動スクリプトのセレクターに間違いがないか確認してください');
    return;
  }

  // ======================================================================== //

  const $form = this;

  // ======================================================================== //

  // オプションのデフォルト値設定

  options = $.extend({
    validation_groups: {},
    validation_rules: {},
    validation_messages: {},
    autokana: [],
    zip2addr: {},
    datepicker: {},
    button_submit: null,
    num_remaining: null,
    recaptcha_sitekey: null,
  }, options);

  options.datepicker = $.extend({
    __default: {},
  }, options.datepicker);

  options.datepicker.__default = $.extend({
    firstDay: 1,
    minDate: 0,
  }, options.datepicker.__default);

  // ======================================================================== //

  // バリデーション初期化

  const validator = this.validate({
    debug: false,
    groups: options.validation_groups,
    rules: options.validation_rules,
    messages: options.validation_messages,
    errorPlacement: function(err, elem) {
      $(elem)
        .parents('.brick-form__row-body')
        .find('.brick-form__message')
        .append(err);
    },
  });

  // ======================================================================== //

  // 残り項目数表示

  if (options.num_remaining) {
    $form.on('mouseup keyup change', function() {
      update_num_remaining(options.num_remaining, validator);
    });

    update_num_remaining(options.num_remaining, validator);
  }

  // ======================================================================== //

  // 必須項目が全て入力されている場合に限ってサブミットボタンを押下可能にする

  if (options.button_submit) {
    $form.on('mouseup keyup change', function() {
      update_submit_disabled(options.button_submit, validator);
    });

    update_submit_disabled(options.button_submit, validator);
  }

  // ======================================================================== //

  // 二重送信防止
  // サブミットボタンが押下されたタイミングで { pointer-events: none; } を設定する

  if (options.button_submit) {
    // NOTE:
    // ここで unload イベントに空のリスナーをセットするのは history.back() で
    // ページを戻ったときにも JavaScript の処理が再実行されるようにするため
    //
    // history.back() で戻ったときに必ず css({ 'pointer-events': 'auto' }) が
    // 実行されなければいけない
    // さもなくばサブミットボタンに style="pointer-events: none" が設定されたまま
    // になり、Submit ボタンが押下できなくなる
    window.addEventListener('unload', function() {});

    const $button_submit = $(options.button_submit);

    $button_submit.css({ 'pointer-events': 'auto' });
    $(options.button_submit).one('click', function(event) {
      $button_submit.css({ 'pointer-events': 'none' });
    });
  }

  // ======================================================================== //

  // ふりがな自動入力

  for (const input in options.autokana) {
    if (!options.autokana.hasOwnProperty(input)) { continue; }

    const rule = options.autokana[input];

    const $input  = $(`input[name='${input}']`);
    const $target = $(`input[name='${rule.target}']`);

    if ($input.length == 0 || $target.length == 0) { continue; }

    $.fn.autoKana($input, $target, { katakana: rule.katakana });
  }

  // ======================================================================== //

  // 郵便番号から住所の自動反映

  for (const input in options.zip2addr) {
    if (!options.zip2addr.hasOwnProperty(input)) { continue; }

    const target = options.zip2addr[input];

    if (target.length == 2) {
      $(`input[name='${input}']`).zip2addr({
        pref: `[name='${target[0]}']`,
        addr: `input[name='${target[1]}']`,
      });
    } else if (target.length == 1) {
      $(`input[name='${input}']`).zip2addr(`input[name='${target[0]}']`);
    }
  }

  // ======================================================================== //

  // デイトピッカー

  {
    const defaultOption = $.extend(
      {},
      $.datepicker.regional['ja'],
      options.datepicker.__default
    );

    $.datepicker.setDefaults(defaultOption);

    $('[data-datepicker]').each(function() {
      const $this = $(this);
      const name = $this.attr('name');
      const option = options.datepicker[name] || {};

      const onSelect = option.onSelect || defaultOption.onSelect;
      option.onSelect = function(dateStr, datepicker) {
        if (onSelect) { onSelect.call(this, dateStr, datepicker); }

        // 日付を選択したタイミングで「残り項目数表示」と「サブミットボタン有効化判定」を
        // 実行する
        validator.element(this);
        $form.trigger('change');
      };

      const onClose = option.onClose || defaultOption.onClose;
      option.onClose = function(dateStr, datepicker) {
        if (onClose) { onClose.call(this, dateStr, datepicker); }

        // タッチデバイスでキーボードを出さないようにするために設定した readonly 属性を
        // ここで外す
        $(this).prop('readonly', false);
      },

      $this.datepicker(option);

      // <input> にフォーカスしたときに入力履歴がサジェストされるのを抑止する
      //
      // 普通に <input autocomplete="off"> と書いてしまうと、history.back() で
      // 戻ってきたときに入力欄がリセットされてしまう
      // それではユーザービリティが低くなるので、<input> にフォーカスしたときだけ
      // autocomplete を無効にする
      //
      // @see {@link https://www.kop.co.jp/blog/website/872/}
      $this.on('focus', function(event) { $this.attr({ autocomplete: 'off' }); });
      $this.on('blur', function(event) { $this.removeAttr('autocomplete'); });

      // タッチデバイスで <input> にフォーカスしたときキーボードを出さないようにする
      // jQuery UI Datepicker によって日付選択用のカレンダーが表示されるので、
      // キーボードを出す必要は無い
      $this.on('touchstart', function(event) {
        const $input = $(this);
        $input.prop('readonly', true);
        $input.trigger('blur');
      });
    });
  }

  // ======================================================================== //

  // <textarea> 高さ自動調整

  $form.find('textarea')
    .on('change keyup keydown paste cut', function() {
      const $textarea = $(this);
      while ($textarea.outerHeight() < this.scrollHeight){
        $textarea.height($textarea.height() + 10);
      }
    });

  // ======================================================================== //

  // パスワードの 表示/非表示 切り替え

  const $units = $form.find('.form__field-unit-password');
  $units.each(function() {
    const $unit = $(this);
    const $input = $unit.find('input');
    const $button = $unit.find('button');

    let visibility = false;

    $button.on('click', function(event) {
      event.preventDefault();

      if (visibility) {
        $input.attr('type', 'text');
        $unit.removeClass('brick-form__field-unit-password--hidden');
        $unit.addClass('brick-form__field-unit-password--shown');
        $button.attr('title', 'パスワードを隠す');
        $button.attr('aria-label', 'パスワードを隠す');
      } else {
        $input.attr('type', 'password');
        $unit.removeClass('brick-form__field-unit-password--shown');
        $unit.addClass('brick-form__field-unit-password--hidden');
        $button.attr('title', 'パスワードを表示');
        $button.attr('aria-label', 'パスワードを表示');
      }

      visibility = !visibility;
    });
  });

  // ======================================================================== //

  // reCAPTCHA 設定

  if (options.recaptcha_sitekey) {
    recaptcha($form, options.button_submit, options.recaptcha_sitekey);
  }
}
