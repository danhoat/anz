<?php
/**
 * 前の記事へ & 次の記事へ
 */
$is_same_term = Arkhe::get_setting( 'pn_link_is_same_term' );

$prev_post = get_adjacent_post( $is_same_term, '', true );
$next_post = get_adjacent_post( $is_same_term, '', false );

if (ok($prev_post)) {
$prev_item = qms4_detail($prev_post->ID);
} else {
  $prev_item = '';
}
if (ok($next_post)) {
$next_item = qms4_detail($next_post->ID);
} else {
  $next_item = '';
}

// タイムスタンプで投稿日と更新日を取得
$prev_the_id             = $prev_post->ID;
$prev_date_timestamp     = get_post_timestamp( $prev_the_id, 'date' );

$next_the_id             = $next_post->ID;
$next_date_timestamp     = get_post_timestamp( $next_the_id, 'date' );
?>


<div class="l-event-rec l-other_article">
  <div class="wp-block-group__inner-container c-postContent">
    <p class="has-text-align-center u-mt-0 is-style-en_title-heading">OTHER NEWS</p>
    <h2 class="has-text-align-center is-style-ja_title-heading">その他の記事を見る</h2>
  </div>
  <ul class="box-list">
<?php if (ok($next_item)) { ?>
  <li class="box-list__item next">
    <a href="<?= $next_item->permalink ?>">
      <div class="box-detail">
        <div class="box-left box-img-hover">
          <?= $next_item->img ?>
        </div>
        <div class="box-right">
          <div class="qms4__post-list__post-date">
            <?php
              if ( $next_date_timestamp )  ark_the__postdate( $next_date_timestamp, 'posted' );
            ?>
          </div>
          <ul class="p-postList__hash p-postList__icon">
            <?= $next_item->category ?>
          </ul>
          <h2 class="qms4__post-list__post-title line-clamp"><?= $next_item->title ?></h2>
          
        </div>
      </div>
    </a>
  </li>
<?php } ?>

<?php if (ok($prev_item)) { ?>
  <li class="box-list__item prev">
    <a href="<?= $prev_item->permalink ?>">
      <div class="box-detail">
        <div class="box-left box-img-hover">
          <?= $prev_item->img ?>
        </div>
        <div class="box-right">
          <div class="qms4__post-list__post-date">
            <?php
              if ( $prev_date_timestamp )  ark_the__postdate( $prev_date_timestamp, 'posted' );
            ?>
          </div>
          <ul class="p-postList__icon">
            <?= $prev_item->category ?>
          </ul>
          <h2 class="qms4__post-list__post-title line-clamp"><?= $prev_item->title ?></h2>

        </div>
      </div>
    </a>
  </li>
<?php } ?>

  </ul>
  <!-- /.l-event-rec__list -->
  <div class="l-list-prev is-content-justification-center wp-block-buttons">
    <div class="wp-block-button is-style-outline is-style-prev"><a class="wp-block-button__link" href="../">一覧に戻る</a></div>
  </div>
</div>
