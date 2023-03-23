<option
  class="brick-form__options-item-<?= $field->name ?>-<?= $option->index ?>"
  value="<?= $option->value ?>"
  <?= $option->selected ? 'selected' : '' ?>

>
  <?= $option->label ?>
</option>
