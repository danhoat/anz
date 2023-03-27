import $ from 'jquery';

const interval = 20;
let timer;

export function update_num_remaining(elem_either_updater, validator, first_time) {
  clearTimeout(timer);
  timer = setTimeout(function() {
    const submitted = $.extend({}, validator.submitted);

    validator.checkForm();
    const num_remaining = Object.keys(validator.errorMap).length;

    validator.submitted = submitted;

    if (typeof elem_either_updater == 'function') {
      const updater = elem_either_updater;

      updater(num_remaining);
    } else if (elem_either_updater) {  // elem_either_updater が CSS セレクター文字列, HTMLElement, jQuery Object の場合を想定
      const $elem = $(elem_either_updater);
      $elem.text(num_remaining);
    }
  }, interval);
}
