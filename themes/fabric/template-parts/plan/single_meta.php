<?php
/**
 * 投稿メタ（head）
 */
$show_posted   = Arkhe::get_setting( 'show_entry_posted' );
$show_modified = Arkhe::get_setting( 'show_entry_modified' );
$show_cat      = Arkhe::get_setting( 'show_entry_cat' );
$show_tag      = Arkhe::get_setting( 'show_entry_tag' );
$show_author   = Arkhe::get_setting( 'show_entry_author' );

$post_data          = get_post();
$the_id             = $post_data->ID;
$author_id          = $post_data->post_author;
$date_timestamp     = get_post_timestamp( $the_id, 'date' );
$modified_timestamp = get_post_timestamp( $the_id, 'modified' );

// 更新日が公開日より遅い場合だけ表示（ただし、更新日だけ表示の時は更新日をそのまま表示する）
if ( $show_modified && $show_posted ) {
	$show_modified = ( $date_timestamp < $modified_timestamp ) ? $show_modified : false;
}

?>
<?php
/**
 * 投稿アイキャッチ画像
 */
$caption = isset( $args['caption'] ) ? $args['caption'] : '';
?>
<figure class="p-entry__thumb">
	<?php
		ark_the__thumbnail( array(
			'class' => 'p-entry__thumb__img',
			'sizes' => '(min-width: 800px) 800px, 100vw',
		) );
	?>
	<?php if ( $caption ) : ?>
		<figcaption class="p-entry__thumb__figcaption"><?php echo wp_kses( $caption, Arkhe::$allowed_text_html ); ?></figcaption>
	<?php endif; ?>
</figure>


<div class="c-postMetas u-flex--aicw">

	<?php
		// カテゴリー・タグ
		Arkhe::get_part( 'single/item/term_list', array(
			'show_cat' => $show_cat,
			'show_tag' => $show_tag,
			'is_head'  => true,
		) );

		// 著者アイコン
		if ( $show_author && $author_id ) :
			$author_icon = Arkhe::get_author_icon_data( $author_id );
			echo '<a href="' . esc_url( $author_icon['url'] ) . '" class="c-postAuthor u-flex--aic">
					<figure class="c-postAuthor__figure">' . wp_kses( $author_icon['avatar'], Arkhe::$allowed_img_html ) . '</figure>
					<span class="c-postAuthor__name">' . esc_html( $author_icon['name'] ) . '</span>
				</a>';
		endif;
	?>
  <?php $item = fabric_load_item(); ?>
  <div class="c-postIcon">
    <ul class="p-postList__icon">
      <?= $item->category ?>

    </ul>
  </div>

</div>

<div class="c-postSpecialIcon">
<?php if (!is_empty($item->special)) { ?>
    <ul class="p-postList__special">
<?php foreach ($item->special as $term) { ?>
      <li>
        <div class="ph <?= $term->slug ?>"></div>
        <div class="text"><?= $term->name ?></div>
      </li>
<?php } ?>
    </ul>
<?php } ?>
</div>

<div class="p-entry__title c-pageTitle">
	<h1 class="c-pageTitle__main"><?php the_title(); ?></h1>
  <div class="c-pageTitle__sub"><?= $item->sub_title ?></div>
</div>

<div class="c-pageInfo">
<?php if (!is_empty($item->term)) { ?>
	<dl>
		<dt>期間</dt>
		<dd><?= $item->term ?></dd>
	</dl>
<?php } ?>
<?php if (!is_empty($item->target)) { ?>
	<dl>
		<dt>対象</dt>
		<dd><?= $item->target ?></dd>
	</dl>
<?php } ?>
</div>

<div class="c-pagePrice">
  <div class="c-pagePrice__main"><?= $item->price ?></div>
	<div class="c-pagePrice__sub"><?= $item->price_sub ?></div>
	<div class="c-pagePrice__privilege"><?= $item->privilege ?></div>
</div>

