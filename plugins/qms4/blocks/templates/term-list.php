<div
  class="widget qms4__term-list"
  data-taxonomy="<?= $taxonomy ?>"
  data-query-key="<?= $query_key ?>"
>
  <ul class="widget__main-list">
<?php foreach ( $terms as $term ) { ?>
<?php
$slug = urldecode( $term->slug );

$active = $query_string->has( $query_key, $slug );
if ( $active ) {
  $query = (string) $query_string->remove( $query_key, $slug );
} else {
  $query = (string) $query_string->add( $query_key, $slug );
}

if ( empty( $query ) ) {
  $href = get_post_type_archive_link( $post_type );
} else {
  $href = get_post_type_archive_link( $post_type ) . '?' . $query;
}
?>
    <li class="<?= $active ? 'active' : '' ?>">
      <a href="<?= $href ?>">
        <span className="widget__main-list__term-name"><?= $term->name ?></span>
<?php if ( $show_count ) { ?>
        <span className="widget__main-list__term-count"><?= $term->count ?></span>
<?php } ?>
      </a>
    </li>
<?php } ?>
  </ul>
  <!-- /.widget__main-list -->
</div>
<!-- /.widget.qms4__term-list -->
