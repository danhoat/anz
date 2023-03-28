<div class="l-search">

<!-- イベント_タクソノミー -->
<?php  // qms4_site_part( 1142 ) ?>
 <!-- copy  qms4/blocks/templates/term-list.php!-->
<div
  class="widget qms4__term-list"
  data-taxonomy="<?= $taxonomy ?>"
  data-query-key="<?= $query_key ?>"
>
  <ul class="widget__main-list">
<?php
$terms = get_terms( array(
    'taxonomy' => 'fair__category',
    'hide_empty' => false,
) );
?>
<?php foreach ( $terms as $term ) { ?>
<?php
$slug = urldecode( $term->slug );
$active = '';

if ( empty( $query ) ) {
  $href = get_post_type_archive_link( $post_type );
} else {
  $href = get_post_type_archive_link( $post_type ) . '?' . $query;
}
$term_link = get_term_link($term);
?>
    <li class="<?= $active ? 'active' : '' ?>">
      <a href="<?= $term_link ?>">
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



</div>
