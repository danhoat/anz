console.info('qms3_form__init.js');

jQuery(function($) {
  $('.js__brick_form').each(function() {
    const $unit = $(this);

    const validation_rules = $unit.data('validation-rules');
    const autokana = $unit.data('autokana');
    const zip2addr = $unit.data('zip2addr');

    console.info({
      validation_rules,
      autokana,
      zip2addr,
    });

    $unit.qms3_form({
      validation_rules,
      autokana,
      zip2addr,
    });
  });
});
