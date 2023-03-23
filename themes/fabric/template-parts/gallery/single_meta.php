<?php $item = fabric_load_item(); ?>


<div class="slidegallery">
  <ul class="main_gallery clearfix">
<?php foreach ($item->photos as $img) { ?>
<?php
$img_width  = $img->width('full');
$img_height = $img->height('full');
$img_ratio  = $img_height / $img_width;
$base_ratio = 1.0;
?>
    <li class="slide-item">
      <div class="ph ph_sys"><img src="<?= $img->src('large') ?>" alt="<?= $img->alt ?>"></div>
    </li>
<?php } ?>
  </ul>
  <ul class="thumb_gallery clearfix">
<?php foreach ($item->photos as $img) { ?>
    <li class="thumbnail-item ig_thumb02"><img src="<?= $img->src ?>" alt=""></li>
<?php } ?>
  </ul>
</div>
<!-- /.slidegallery -->
