<?php
$item = fabric_load_item();

$param = array();
$param[ 'area' ] = $item->area;
$list = qms4_list( 'event', $param );
?>

<ul>
<?php foreach ( $list as $sub_item ) { ?>
  <li><?= $sub_item->title ?></li>
<?php } ?>
</ul>
