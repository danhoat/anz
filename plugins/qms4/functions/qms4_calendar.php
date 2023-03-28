<?php

function sample_html($post){ ?>
	<li class="p-postList__item">
	<a href="#" class="p-postList__link">
		<div class="p-postList__thumb c-postThumb" data-has-thumb="1">
	<figure class="c-postThumb__figure">
		<img width="1024" height="683" src="https://www.eidai-house.co.jp/wp-content/uploads/2023/01/7a4f161d18c42b6563072ee86095cc94-1024x683.jpg" alt="" class="c-postThumb__img" srcset="https://www.eidai-house.co.jp/wp-content/uploads/2023/01/7a4f161d18c42b6563072ee86095cc94-1024x683.jpg 1024w, https://www.eidai-house.co.jp/wp-content/uploads/2023/01/7a4f161d18c42b6563072ee86095cc94-300x200.jpg 300w, https://www.eidai-house.co.jp/wp-content/uploads/2023/01/7a4f161d18c42b6563072ee86095cc94-768x513.jpg 768w, https://www.eidai-house.co.jp/wp-content/uploads/2023/01/7a4f161d18c42b6563072ee86095cc94.jpg 1536w" sizes="(min-width: 600px) 400px, 40vw" loading="lazy">	</figure>
</div>
		<div class="p-postList__body">
			<h2 class="p-postList__title"><?= $post->post_title;?></h2>
            <div class="qms4__post-list__post-date">3/17-4/30</div>

							<div class="p-postList__excerpt">
					<p>Information モデルハウス完成見学会‼︎ 佐世保港を一望できる好立地の分譲地 高梨町にてモデルハウス見学会開催中！「眺望を楽しめる立地を活かした家に住みたい」そんなオーナー様のご要望を形にしました。また、キッチンはホームパーティーも楽しめるアイランドキッチン。 是非この機会に実物大の建売住宅を見に来てください。 事前来場予約さらに、アンケートをお答…</p>
				</div>
						<div class="p-postList__meta c-postMetas u-flex--aicw">
	<div class="p-postList__times c-postTimes u-color-thin u-flex--aic">
	<time class="c-postTimes__item u-flex--aic -posted" datetime="2023-01-18"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="arkhe-svg-posted c-postMetas__icon" width="1em" height="1em" viewBox="0 0 40 40" role="img" aria-hidden="true" focusable="false"><path d="M21,18.5v-9C21,9.2,20.8,9,20.5,9h-2C18.2,9,18,9.2,18,9.5v12c0,0.3,0.2,0.5,0.5,0.5h10c0.3,0,0.5-0.2,0.5-0.5v-2 c0-0.3-0.2-0.5-0.5-0.5h-7C21.2,19,21,18.8,21,18.5z"></path><path d="M20,39C9.5,39,1,30.5,1,20S9.5,1,20,1s19,8.5,19,19S30.5,39,20,39z M20,3.8C11.1,3.8,3.8,11.1,3.8,20S11.1,36.2,20,36.2 S36.2,28.9,36.2,20S28.9,3.8,20,3.8z"></path></svg>2023年1月18日</time></div>

<!-- archiveにカテゴリ表示 -->
<ul class="p-postList__icon">
  <li class="icon">
    佐世保市
  </li>

  <li class="icon" style="background-color:#d1ac79">完成見学会</li>
</ul>
</div>
		</div>
	</a>
</li>
<?php }
function qms4_list_events_by_date(){

	$ymd = isset($_GET['ymd']) ? $_GET['ymd']: date('Y-m-d');
	//$meta_value = '2023-03-27';
	$meta_value = $ymd;
	$meta_key = 'qms4__event_date';
	global $wpdb;

	$sql =  $wpdb->prepare(
      	"SELECT s.meta_value as id FROM $wpdb->postmeta m
      		LEFT JOIN $wpdb->postmeta s ON m.post_id = s.post_id
      			WHERE   m.meta_key = %s AND m.meta_value = %s
      					AND s.meta_key = %s GROUP BY s.meta_value
      			",
		      $meta_key,
		      $meta_value,
		      'qms4__parent_event_id'
   );

	$parent_ids = $wpdb->get_results($sql, ARRAY_A);
	$ids = array();
	foreach( $parent_ids as $key=>$value){
		$ids[] = (int) $value['id'];
	}

	$args = array(
		'post_type' => 'fair',
		'post_status' => 'publish',
		'post__in' => $ids
	);
	echo '<ul class="p-postList -type-list">';
	$list = new WP_Query($args);
	if($list->have_posts() ){
		while($list->have_posts() ){
			$list->the_post();
			global $post;
			sample_html($post);
		}
	} else{
		_e('Not post found.');
	}
	echo '</div>';


}

add_shortcode( 'events_date', 'qms4_list_events_by_date' );