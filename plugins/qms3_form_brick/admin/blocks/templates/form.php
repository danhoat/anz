<form
  action="<?= $form->action ?>"
  method="POST"
  enctype="<?= $form->enctype ?>"
  class="js__brick_form"
  data-validation-rules="<?= esc_attr(json_encode($form->metadata->validation_rules(), JSON_UNESCAPED_UNICODE)) ?>"
  data-autokana="<?= esc_attr(json_encode($form->metadata->autokana(), JSON_UNESCAPED_UNICODE)) ?>"
  data-zip2addr="<?= esc_attr(json_encode($form->metadata->zip2addr(), JSON_UNESCAPED_UNICODE)) ?>"
>
  <div class="brick-form">
    <div class="brick-form__content">
<?php foreach ($form as $block) { ?>
      <?= $block->render() ?>

<?php } ?>
    </div>
    <!-- /.brick-form__content -->

<?php if ($inner_content) { ?>
    <div class="l-form-container__inner-content">
      <?= $inner_content ?>

    </div>
    <!-- /.l-form-container__inner-content -->
<?php } ?>

    <div class="brick-buttons">
      <?= $form->buttons ?>

    </div>
    <!-- /.brick-buttons -->
  </div>
  <!-- /.brick-form -->
</form>
