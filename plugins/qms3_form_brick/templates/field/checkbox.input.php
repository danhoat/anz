<div class="
  brick-form__field-unit
  brick-form__field-unit-checkbox
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($field->prepend) { ?>
  <div class="brick-form__prepend"><?= $field->prepend ?></div>
<?php } ?>
  <div class="brick-form__field kkk">
    <input type="hidden" name="<?= $field->name ?>" value="">
    <div class="brick-form__options brick-form__options-checkbox brick-form__options-name-<?= $field->name ?>">
<?php foreach ($field->options as $option) { ?>
      <?= $option->render('input') ?>

<?php } ?>
    </div>
    <!-- /.brick-form__options -->
  </div>
  <!-- /.brick-form__field -->
<?php if ($field->append) { ?>
  <div class="brick-form__append"><?= $field->append ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-checkbox -->
