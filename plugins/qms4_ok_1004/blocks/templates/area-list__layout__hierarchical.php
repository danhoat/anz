<div
  class="qms4__area-list"
  data-layout="hierarchical"
>
  <ul
    class="qms4__area-list__list"
    data-max-depth="<?= $post_forest->map_field( array( $this, 'max_depth' ), 'length', 1 ) ?>"
  >
<?php foreach ( $post_forest as $post_tree ) { ?>
<?php $area = urldecode( $post_tree->wp_post()->post_name ); ?>
    <li
      class="qms4__area-list__list-item"
      <?= $this->active( $area ) ? 'data-active' : '' ?>

    >
      <a href="<?= $this->href( $area ) ?>" <?= $target ?>>
        <?= $post_tree->wp_post()->post_title ?>

      </a>
<?php if ( ! $post_tree->is_leaf() ) { ?>
      <ul
        class="qms4__area-list__sub-list"
        data-max-depth="<?= $post_tree->sub_forest()->map_field( array( $this, 'max_depth' ), 'length', 1 ) ?>"
      >
<?php foreach ( $post_tree->sub_forest() as $sub_post_tree ) { ?>
<?php $area = urldecode( $sub_post_tree->wp_post()->post_name ); ?>
        <li
          class="qms4__area-list__sub-list-item"
          <?= $this->active( $area ) ? 'data-active' : '' ?>

        >
          <a href="<?= $this->href( $area ) ?>" <?= $target ?>>
            <?= $sub_post_tree->wp_post()->post_title ?>

          </a>
        </li>
<?php } ?>
      </ul>
<?php } ?>
    </li>
<?php } ?>
  </ul>
</div>
<!-- /.qms4__area-list -->
