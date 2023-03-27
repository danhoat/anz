<div
  class="qms4__area-list"
  data-layout="flat"
>
  <ul
    class="qms4__area-list__list"
    data-max-depth="1"
  >
<?php foreach ( $wp_posts as $wp_post ) { ?>
<?php $area = urldecode( $wp_post->post_name ); ?>
    <li
      class="qms4__area-list__list-item"
      <?= $this->active( $area ) ? 'data-active' : '' ?>

    >
      <a href="<?= $this->href( $area ) ?>" <?= $target ?>>
        <?= $wp_post->post_title ?>

      </a>
    </li>
<?php } ?>
  </ul>
</div>
<!-- /.qms4__area-list -->
