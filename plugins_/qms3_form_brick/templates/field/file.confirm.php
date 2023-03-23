<div class="
  brick-form__field-unit
  brick-form__field-unit-file
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
<?php if ($field->prepend) { ?>
  <div class="brick-form__prepend"><?= $field->prepend ?></div>
<?php } ?>
  <div class="brick-form__field">
    <div class="brick-form__field-display brick-form__field-display-file">
      <a href="<?= $field->value->url ?>" target="_blank">
        <span class="brick-form__field-display-file__filename"><?= $field->value->filename ?></span>
        <span class="brick-form__field-display-file__filesize">(<?= $this->human_readable_filesize($field->value->filesize) ?>)</span>
      </a>
    </div>
    <!-- /.brick-form__field-display.brick-form__field-display-file -->
  </div>
  <!-- /._field -->
<?php if ($field->append) { ?>
  <div class="brick-form__append"><?= $field->append ?></div>
<?php } ?>
<?php if ($hidden) { ?>
  <div class="brick-form__hidden"><?= $hidden ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-file -->
