<div class="
  brick-form__field-unit
  brick-form__field-unit--error
  brick-form__field-unit-file
  <?= $field->required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional' ?>

  brick-form__field-unit-name-<?= $field->name ?>

">
  <div class="brick-form__field">
    <div class="brick-form__field-error">
      <p>ファイルサイズが大きすぎます。</p>
    </div>
    <!-- /.brick-form__field-display.brick-form__field-display-file -->
  </div>
  <!-- /._field -->
<?php if ($hidden) { ?>
  <div class="brick-form__hidden"><?= $hidden ?></div>
<?php } ?>
</div>
<!-- /.brick-form__field-unit.brick-form__field-unit-file -->
