<div class="
  brick-form__field-unit
  brick-form__field-unit-textarea
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($field->prepend) { ?>
  <div class="brick-form__prepend"><?= $field->prepend ?></div>
<?php } ?>
  <div class="brick-form__field">
    <textarea
      id="brick-form__field-name-<?= $field->name ?>"
      class="brick-form__field-name-<?= $field->name ?>"
      name="<?= $field->name ?>"
      placeholder="<?= $field->placeholder ?>"
      <?= $field->required ? 'required' : '' ?>

      <?= $field->attributes ?>

    ><?= $field->value ?></textarea>
  </div>
  <!-- /.brick-form__field -->
<?php if ($field->append) { ?>
  <div class="brick-form__append"><?= $field->append ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-textarea -->
