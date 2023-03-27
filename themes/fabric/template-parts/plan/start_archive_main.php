<?php $item = fabric_load_item(); ?>
<?php
$param = array();
$param['count'] = 1;
$list = qms4_list( 'plan', $param );

?>

<div class="l-plan-rec">
  <div class="l-plan-rec__list">
<?php foreach ( $list as $sub_item ) { ?>
    <div class="l-plan-rec__list-item">
      <a href="<?= $sub_item->permalink ?>" target="_self">
        <div class="qms4__post-list__post-thumbnail l-plan-rec__post-thumbnail" data-aspect-ratio="3:2" data-object-fit="cover"><?= $sub_item->img ?></div>
        <div class="qms4__post-list__inner l-plan-rec__inner">
          <div class="p-postList__icons">
            <ul class="p-postList__icon">
              <?= $sub_item->category ?>
            </ul>
          </div>
          <!-- /.p-postList__icons -->
          <div class="qms4__post-list__post-title l-plan-rec__post-title line-clamp" data-num-lines-pc="2" data-num-lines-sp="2" title="<?= $sub_item->title ?>"><?= $sub_item->title ?></div>
<?php if (!is_empty($item->price)||!is_empty($item->price_sub)) { ?>
			    <div div class="qms4__post-list__post-price l-plan-rec__post-price">
<?php if (!is_empty($item->price)) { ?>
      	    <?= $item->price ?>
<?php } ?>
<?php if (!is_empty($item->price)&&!is_empty($item->price_sub)) { ?>／<?php } ?>
<?php if (!is_empty($item->price_sub)) { ?>
            <?= $item->price_sub ?>
<?php } ?>
			    </div>
<?php } ?>
<?php if (!is_empty($item->privilege)) { ?>
          <div class="qms4__post-list__post-privilege l-plan-rec__post-privilege line-clamp"><?= $sub_item->privilege ?></div>
<?php } ?>
        </div>
      </a>
    </div>
    <!-- /.l-plan-rec__list-item -->
<?php } ?>

  </div>
  <!-- /.l-plan-rec__list -->
</div>


<div class="l-other-plan__title">
  <div class="wp-block-group__inner-container c-postContent">
    <p class="has-text-align-center u-mt-0 is-style-en_title-heading">OTHER PLAN</p>
    <h2 class="has-text-align-center is-style-ja_title-heading">その他のプラン</h2>
  </div>
</div>
