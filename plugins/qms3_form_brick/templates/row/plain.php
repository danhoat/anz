<?php
$field_strs = array();
foreach ($row->fields as $field) {
  $field_strs[] = $field->render('PLAIN');
}
?>
<?= $row->label ?>　：　<?= join(' ', $field_strs) ?>
