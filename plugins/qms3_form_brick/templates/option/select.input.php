<option
  class="brick-form__options-item-<?= $field->name ?>-<?= $option->index ?>"
  value="<?= $option->value ?>"
  <?= $option->selected ? 'selected' : '' ?>

>
  <?php if( is_valid_date($option->label)){
     $date = date_create($option->label);
     echo $date->format('Y年n月j日 D');
  } else{
    echo $option->label;
  }

   ?>
</option>
