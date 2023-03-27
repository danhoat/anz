<div
  class="brick-form__row brick-form__row--step-input brick-form__row--hidden"
  style="display:none"
>
<?php foreach ($row->fields as $field) { ?>
  <?= $field->render('input') ?>

<?php } ?>
</div>
<!-- /.brick-form__row -->
