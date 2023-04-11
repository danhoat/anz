<?php if ( ! $server_side_rendering ) { ?>
<div
  class="qms4__post-list"
  data-layout="<?= $layout ?>"
  data-num-columns-pc="<?= $num_columns_pc ?>"
  data-num-columns-sp="<?= $num_columns_sp ?>"
  data-num-posts-pc="<?= $num_posts_pc ?>"
  data-num-posts-sp="<?= $num_posts_sp ?>"
>
<?php } ?>
  <div class="qms4__post-list__list <?= $className ?>  <?= $custom_style ?>">
<?php foreach ( $list as $item ) { ?>
  <?php

    $week = '';
    $item->custom_style = $custom_style;
    if($item->post_type == 'fair'){
      $item->event_date   = qms4_get_event_date($item->ID);
      $event_date         = new \DateTimeImmutable( $item->event_date );// add argument wp_timezone after;
      $item->event_time_stamp = $event_date->getTimestamp()+20000;
      $week =  strtolower(date('D', $item->event_time_stamp));

    }

    $item->excerpt_lenght = apply_filters($item->post_type.'_execept_lenght', 50);
  ?>
    <div class="qms4__post-list__list-item qms4__list_item_type_<?= $item->post_type ?> <?= $week ?>">
      <a
        href="<?= $item->permalink ?>"
        target="<?= $link_target === '__custom' ? $link_target_custom : $link_target ?>"
      >
      <?= $renderer->render( $item ) ?>
      </a>
    </div>
    <!-- /.qms4__post-list__list-item -->
<?php } ?>
  </div>
  <!-- /.qms4__post-list__list -->

<?php if ( ! $server_side_rendering ) { ?>
</div>
<!-- /.qms4__post-list -->
<?php } ?>
