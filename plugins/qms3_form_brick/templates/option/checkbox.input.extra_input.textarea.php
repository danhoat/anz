<div class="
  brick-form__options-item
  brick-form__options-item--checkbox
  brick-form__options-item--checkbox-alt
  brick-form__options-item--checkbox-alt-textarea
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
    <span class="brick-form__extra-input-label"><?= $option->label ?></span>
<?php } ?>
    <sapn class="brick-form__extra-input form__extra-input--textarea">
      <textarea
        name="<?= $option->extra_input_name ?>"
        placeholder="<?= $option->extra_input_placeholder ?>"
      ><?= $option->extra_input_value ?></textarea>
    </sapn>
  </label>
</div>
<!-- /.brick-form__options-item.brick-form__options-item--checkbox.brick-form__options-item--checkbox-alt.brick-form__options-item--checkbox-alt-textarea -->
