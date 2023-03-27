<?php $item = fabric_load_item(); ?>

<!-- archiveにカテゴリ表示 -->
<ul class="p-postList__icon">
<?php if ( ok( $item->area ) ) { ?>
  <li class="icon">
    <?= $item->area->title ?>

  </li>
<?php } ?>

  <?= $item->category ?>

</ul>
