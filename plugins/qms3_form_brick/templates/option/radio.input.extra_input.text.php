<div class="
  brick-form__options-item
  brick-form__options-item--radio
  brick-form__options-item--radio-alt
  brick-form__options-item--radio-alt-text
">
  <input
    id="brick-form__options-item-<?= $field->name ?>-<?= $option->index ?>"
    type="radio"
    name="<?= $field->name ?>"
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
    <sapn class="brick-form__extra-input form__extra-input--text">
      <input
        type="text"
        name="<?= $option->extra_input_name ?>"
        value="<?= $option->extra_input_value ?>"
        placeholder="<?= $option->extra_input_placeholder ?>"
      >
    </sapn>
  </label>
</div>
<!-- /.brick-form__options-item.brick-form__options-item--radio.brick-form__options-item--radio-alt.brick-form__options-item--radio-alt-text -->
