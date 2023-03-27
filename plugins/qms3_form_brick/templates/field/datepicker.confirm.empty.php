<div class="
  brick-form__field-unit
  brick-form__field-unit-datepicker
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit--empty
  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($hidden) { ?>
  <div class="brick-form__hidden"><?= $hidden ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-datepicker.brick-form__field-unit--empty -->
