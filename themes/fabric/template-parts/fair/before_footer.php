<?php if ( !is_single() ) { ?>
<div class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-28">
  <div class="wp-block-button has-custom-width wp-block-button__width-25 has-custom-font-size is-style-outline has-small-font-size" style="font-style:normal;font-weight:400"><a class="wp-block-button__link wp-element-button" href="https://roseun-charme.jp/test/fair_calendar/" style="padding-top:0.8rem;padding-right:0.8rem;padding-bottom:0.8rem;padding-left:0.8rem">カレンダーから探す</a></div>
  <div class="wp-block-button has-custom-width wp-block-button__width-25 has-custom-font-size is-style-fill_action01 has-small-font-size" style="font-style:normal;font-weight:400"><a class="wp-block-button__link wp-element-button" href="https://roseun-charme.jp/test/reserve/" style="padding-top:0.8rem;padding-right:0.8rem;padding-bottom:0.8rem;padding-left:0.8rem">見学予約はこちら</a></div>
</div>
<?php } ?>
<?php if ( is_single() ) { ?>
<?php $item = fabric_load_item(); ?>
<?php
$param = array();
$param['count'] = 2;
$param['area'] = $item->area->slug;
// $list = qms4_list( 'fair', $param );
$ymd = isset($_GET['ymd'])  ? $_GET['ymd'] : '';

if(  empty($ymd)  || !is_valid_date($ymd ) ){
    $ymd = qms4_get_event_date($item->ID);
}



$meta_key = 'qms4__event_date';
global $wpdb;
$sql =  $wpdb->prepare("
    SELECT SQL_CALC_FOUND_ROWS mt1.meta_value as event_id
    FROM $wpdb->posts p
    INNER JOIN $wpdb->postmeta m ON ( p.ID = m.post_id )
    INNER JOIN $wpdb->postmeta AS mt1 ON ( p.ID = mt1.post_id ) WHERE
        ( ( m.meta_key = %s AND CAST(m.meta_value AS DATE) = %s ) AND( mt1.meta_key = %s ) )
        AND p.post_type = %s AND (p.post_status = 'publish')
    GROUP BY mt1.meta_value",
    'qms4__event_date', $ymd, 'qms4__parent_event_id', 'fair__schedule' );

$result = $wpdb->get_results($sql, ARRAY_A);
$event_ids = array();
if($result){
    foreach($result as $reg){
        $id = (int) $reg['event_id'];
        if($id !== $item->ID)
            $event_ids[] = $id;
    }
}
$param['post__in'] = array(-1);

if( !empty($event_ids) ) $param['post__in'] = $event_ids;

$args = array(
    'post_type'         => 'fair',
    'post__in'          => $event_ids,
    'post__not_in'      => $item->ID,
    'post_status'       => 'publish',
    'posts_per_page'    => 2
);

$list = new WP_Query($args);

?>

<?php if ( ok( $list ) ) { ?>
<div class="l-event-flow">
  <div class="wp-block-group__inner-container c-postContent">
    <p class="has-text-align-center u-mt-0 is-style-en_title-heading">RESERVATION FLOW</p>
    <h2 class="has-text-align-center is-style-ja_title-heading">ご予約の流れ</h2>
  </div>
  <ul>
    <li>
      <dl>
        <dt>01</dt>
        <dd>フェアを選ぶ</dd>
      </dl>
      <p>
        参加したいフェアをお選びください。<br>
        日付からも種類からも<br class="pc">お選びできます。
      </p>
    </li>
    <li>
      <dl>
        <dt>02</dt>
        <dd>予約する</dd>
      </dl>
      <p>
        気になったフェアの「開催時間」から<br class="pc">
        ご希望の時間を選んで<br class="pc">
        フォームからご予約ください。
      </p>
    </li>
    <li>
      <dl>
        <dt>03</dt>
        <dd>スタッフから連絡</dd>
      </dl>
      <p>
        ご予約完了後、<br class="pc">
        スタッフからお知らせの連絡を<br class="pc">
        させていただきます。
      </p>
    </li>
    <li>
      <dl>
        <dt>04</dt>
        <dd>フェア当日</dd>
      </dl>
      <p>
        お知らせした日時にお越しください。<br>
        ※お車でももちろん大丈夫です！
      </p>
    </li>
  </ul>
</div>
<div class="l-event-rec l-other_article">
  <div class="wp-block-group__inner-container c-postContent">
    <p class="has-text-align-center u-mt-0 is-style-en_title-heading">OTHER BRIDAL FAIR</p>
    <h2 class="has-text-align-center is-style-ja_title-heading">おすすめフェア</h2>
  </div>

  <ul class="box-list">
<?php foreach ( $list->posts as $post ) { ?>
    <?php $item = qms4_detail($post->ID); ?>
    <li class="box-list__item">
      <a href="<?= $item->permalink ?>">
        <div class="box-detail">
          <div class="box-left box-img-hover">
            <?= $item->img ?>
          </div>
          <div class="box-right">
            <h2 class="qms4__post-list__post-title line-clamp"><?= $item->title ?></h2>
            <!-- <div class="qms4__post-list__post-date">開催日：<?= $item->date_html ?></div> -->
<?php if ( ok( $item->special ) ) { ?>
            <ul class="p-postList__icon ">
                <?= $item->special ?>
            </ul>
<?php } ?>
<?php if ( ok( $item->category ) ) { ?>
            <ul class="p-postList__icon p-postList__hash">
                <?= $item->category ?>
            </ul>
<?php } ?>
            </div>
        </div>
      </a>
    </li>
<?php } ?>
  </ul>
  <!-- /.l-event-rec__list -->
</div>
<?php } ?>


<section class="contact_btn pc">
  <a href="../../reserve_c/?id=<?= $item->id ?>">
    <span class="tit en">1分で完了！</span>このフェアを<br>予約
  </a>
</section>
<section class="h_nav sp clearfix">
	<ul>
    <li class="contact">
      <span class="icon_f">1分で<br>完了！</span>
      <a href="../../reserve_c/?id=<?= $item->id ?>">
        フェア予約はこちら
      </a>
    </li>
  </ul>
</section>
<?php } ?>
