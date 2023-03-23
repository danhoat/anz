<div class="
  brick-form__options-item
  brick-form__options-item--checkbox
  <?= $option->figure ? 'brick-form__options-item--checkbox-with-figure' : '' ?>

">
  <input
    id="brick-form__options-item-<?= $field->name ?>-<?= $option->index ?>"
    type="checkbox"
    name="<?= $field->name ?>[]"
    value="<?= $option->value ?>"
    <?= $option->checked ? 'checked' : '' ?>

  >
  <label for="brick-form__options-item-<?= $field->name ?>-<?= $option->index ?>">
<?php if ($option->figure) { ?>
    <figure>
      <img src="<?= $option->figure ?>" alt="<?= $option->label ?>">
      <figcaption><?= $option->label ?></figcaption>
    </figure>
<?php } else { ?>
    <?= $option->label ?>

<?php } ?>
  </label>
</div>
<!-- /.brick-form__options-item.brick-form__options-item--checkbox -->
