<div class="
  brick-form__field-unit
  brick-form__field-unit-select
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($field->prepend) { ?>
  <div class="brick-form__prepend"><?= $field->prepend ?></div>
<?php } ?>
  <div class="brick-form__field">
    <select
      id="brick-form__options-<?= $field->name ?>"
      class="brick-form__options brick-form__options-select brick-form__options-name-<?= $field->name ?>"
      name="<?= $field->name ?>"
      <?= $field->required ? 'required' : '' ?>

      <?= $field->attributes ?>

    >
<?php foreach ($field->options as $option) { ?>
      <?= $option->render('input') ?>

<?php } ?>
    </select>
  </div>
  <!-- /.brick-form__field -->
<?php if ($field->append) { ?>
  <div class="brick-form__append"><?= $field->append ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-select -->
