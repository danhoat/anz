<div class="
  brick-form__field-unit
  brick-form__field-unit-address
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($field->prepend) { ?>
  <div class="brick-form__prepend"><?= $field->prepend ?></div>
<?php } ?>
  <div class="brick-form__field">
    <input
      id="brick-form__field-name-<?= $field->name ?>"
      class="brick-form__field-name-<?= $field->name ?>"
      type="text"
      name="<?= $field->name ?>"
      value="<?= $field->value ?>"
      placeholder="<?= $field->placeholder ?>"
      autocomplete="street-address"
      <?= $field->required ? 'required' : '' ?>

      <?= $field->attributes ?>

    >
  </div>
  <!-- /.brick-form__field -->
<?php if ($field->append) { ?>
  <div class="brick-form__append"><?= $field->append ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-address -->
