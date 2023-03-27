<div class="l-buttons">
  <div class="is-style-outline"><a href="/event_calendar/">カレンダーから探す</a></div>
  <div class="is-style-fill_action01"><a href="/reserve/">見学予約はこちら</a></div>
</div>

<?php if ( is_single() ) { ?>
<?php $item = fabric_load_item(); ?>
<?php
$param = array();
$param['count'] = 2;
$param['area'] = $item->area->slug;
$list = qms4_list( 'fair', $param );
?>

<?php if ( ok( $list ) ) { ?>
<div class="l-event-rec">
  <div class="p-title-en">
    <h2 class="has-text-align-center is-style-ja_title-heading"><?= $item->area->title ?><br class="sp">おすすめイベント</h2>
    <h6 class="has-text-align-center u-mt-0 is-style-en_title-heading"><strong>RECOMMEND</strong></h6>
  </div>
  <ul class="box-list">
<?php foreach ( $list as $item ) { ?>
    <li class="box-list__item">
      <a href="<?= $item->permalink ?>">
        <div class="box-detail">
          <div class="box-left box-img-hover">
            <?= $item->img ?>
          </div>
          <div class="box-right">
            <h2 class="qms4__post-list__post-title line-clamp"><?= $item->title ?></h2>
            <div class="qms4__post-list__post-date">開催日：<?= $item->event_date ?></div>
              <ul class="p-postList__icon">
<?php if ( ok( $item->area ) ) { ?>
                <li class="icon">
                  <?= $item->area->title ?>

                </li>
<?php } ?>
                <?= $item->category ?>

              <ul>
            </div>
        </div>
      </a>
    </li>
<?php } ?>
  </ul>
  <!-- /.l-event-rec__list -->
</div>
<?php } ?>
<?php } ?>
