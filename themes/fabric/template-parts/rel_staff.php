<?php $item = fabric_load_item(); ?>
<?php
$param['count'] = 4;
?>
<div class="l-works-staff">
  <div class="l-works-staff__list">
<?php foreach ( $item->staff as $sub_item ) { ?>
    <div class="l-works-staff__list-item">
      <a href="<?= $sub_item->permalink ?>" target="_self">
        <div class="qms4__post-list__post-thumbnail l-works-staff__post-thumbnail" data-aspect-ratio="3:2" data-object-fit="cover"><?= $sub_item->img ?></div>
        <div class="qms4__post-list__post-title l-works-staff__post-title" data-num-lines-pc="2" data-num-lines-sp="2" title="<?= $sub_item->title ?>"><?= $sub_item->title ?></div>
        <div class="p-postList__icons">
          <ul class="p-postList__icon">
            <?= $sub_item->position ?>

          </ul>
          <ul class="p-postList__hash p-postList__icon">
            <?= $sub_item->dept ?>

          </ul>
        </div>
        <!-- /.p-postList__icons -->
      </a>
    </div>
    <!-- /.l-works-staff__list-item -->
<?php } ?>

  </div>
  <!-- /.l-works-staff__list -->
</div>


