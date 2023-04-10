<?php $item = fabric_load_item(); ?>

<!-- archiveにカテゴリ表示 -->

<?php farbic_show_fair_icons($item->ID);?>
<ul class="p-postList__icon fair_list_categories p-postList__hash">
  <?php if ( ok( $item->area ) ) { ?>
    <li class="icon">
      <?= $item->area->title ?>

    </li>
  <?php } ?>

  <?= $item->category ?>


</ul>