<?php if ($titles) { ?>
<div class="brick-form__title-row">
<?php foreach ($titles as $title) { ?>
  <h3 class="brick-form__title-row-title brick-form__title-row-title-<?= $this->slugify($title->name) ?>">
    <?= $this->br($title->label) ?>

  </h3>
<?php } ?>
</div>
<!-- /.brick-form__row-title -->
<?php } ?>

<?php if ($fields) { ?>
<?php
$class_names = [];
foreach ($fields as $field) {
  $class_names[] = "brick-form__row-name-{$field->name}";
}
?>
<div class="
  brick-form__row
  brick-form__row--step-confirm
  <?= $row->required ? 'brick-form__row--required' : 'brick-form__row--optional' ?>

  <?= join("\n  ", $class_names) ?>

">
  <div class="brick-form__row-header">
    <h4 class="brick-form__label">
      <span><?= $this->br($row->label) ?></span>
    </h4>
<?php if ($row->header_notices) { ?>
    <div class="brick-form__header-notice">
<?php foreach ($row->header_notices as $header_notice) { ?>
      <div class="brick-form__header-notice-item"><?= $this->br($header_notice) ?></div>
<?php } ?>
    </div>
    <!-- /.brick-form__header-notice -->
<?php } ?>
  </div>
  <!-- /.brick-form__row-header -->

  <div class="brick-form__row-body">
    <div class="brick-form__field-group">
<?php foreach ($row->fields as $field) { ?>
        <?= $field->render('confirm') ?>

<?php } ?>
    </div>
    <!-- /.brick-form__field-group -->

<?php if ($row->body_notices) { ?>
    <div class="brick-form__body-notice">
<?php foreach ($row->body_notices as $body_notice) { ?>
      <div class="brick-form__body-notice-item"><?= $this->br($body_notice) ?></div>
<?php } ?>
    </div>
    <!-- /.brick-form__body-notice -->
<?php } ?>
  </div>
  <!-- /.brick-form__row-body -->
</div>
<!-- /.brick-form__row -->
<?php } ?>
