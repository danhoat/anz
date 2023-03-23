<?php
$row_strs = array();
foreach ($block->rows as $row) {
  $row_strs[] = $row->render('plain');
}

echo join("\n", $row_strs);
