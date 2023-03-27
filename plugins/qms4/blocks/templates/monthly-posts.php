<div
  class="widget qms4__monthly-posts js__qms4__monthly-posts"
  data-endpoint="<?= home_url( "/wp-json/qms4/v1/monthly-posts/{$post_type}/%year%/" ) ?>"
  data-link="<?= get_post_type_archive_link( $post_type ) . '?ym=%year%%month%' ?>"
  data-current="<?= $query_string->get( 'ym' ) ?>"
>
  <div class="widget__main">
    <div class="widget__main-nav">
      <div class="widget__main-prev">
        <button
          type="button"
          class="js__qms4__monthly-posts__button-prev"
        >prev</button>
      </div>
      <div class="widget__main-year js__qms4__monthly-posts__year"><?= $this_year ?></div>
      <div class="widget__main-next">
        <button
          type="button"
          class="js__qms4__monthly-posts__button-next"
        >next</button>
      </div>
    </div>
    <!-- /.widget__main-nav -->

    <ul class="widget__main-month js__qms4__monthly-posts__list">
<?php foreach ( $rows as $row ) { ?>
      <li <?= $this->active( $row[ 'month' ] ) ? 'data-active' : '' ?>>
        <a href="<?= $this->href( $row[ 'month' ] ) ?>">
          <?= $row[ 'month' ] ?>月（<?= $row[ 'count' ] ?>）
        </a>
      </li>
<?php } ?>
    </ul>
    <!-- /.widget__main-month -->
  </div>
  <!-- /.widget__main -->
</div>
<!-- /.widget.qms4__monthly-posts.js__qms4__monthly-posts -->
