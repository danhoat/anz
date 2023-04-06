<?php $item = fabric_load_item(); ?>

<!-- archiveにカテゴリ表示 -->
<ul class="p-postList__icon fair_list_categories">
  <?php if ( ok( $item->area ) ) { ?>
    <li class="icon">
      <?= $item->area->title ?>

    </li>
  <?php } ?>

  <?= $item->category ?>


</ul>
<?php farbic_show_fair_icons($item->ID);?>
