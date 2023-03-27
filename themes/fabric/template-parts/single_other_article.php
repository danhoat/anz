<?php $item = fabric_load_item(); ?>


<div class="l-event-rec l-other_article">

  <ul class="box-list">
<?php if ($item) { ?>
  <li class="box-list__item">
    <a href="<?= $item->permalink ?>">
      <div class="box-detail">
        <div class="box-left box-img-hover">
          <?= $item->img ?>
        </div>
        <div class="box-right">
          <h2 class="qms4__post-list__post-title line-clamp"><?= $item->title ?></h2>
          <div class="qms4__post-list__post-date"><?= $item->date ?></div>
          <ul class="p-postList__icon">
<?php if ( ok( $item->area ) ) { ?>
                <li class="icon">
                  <?= $item->area->title ?>
                </li>
<?php } ?>
            <?= $item->category ?>

            <?= $item->position ?>

          <ul>
          <ul class="p-postList__hash p-postList__icon">
            <?= $item->dept ?>

          </ul>
        </div>
      </div>
    </a>
  </li>
<?php } ?>
<?php if ($item) { ?>
  <li class="box-list__item">
    <a href="<?= $item->permalink ?>">
      <div class="box-detail">
        <div class="box-left box-img-hover">
          <?= $item->img ?>
        </div>
        <div class="box-right">
          <h2 class="qms4__post-list__post-title line-clamp"><?= $item->title ?></h2>
          <div class="qms4__post-list__post-date"><?= $item->date ?></div>
          <ul class="p-postList__icon">
<?php if ( ok( $item->area ) ) { ?>
                <li class="icon">
                  <?= $item->area->title ?>
                </li>
<?php } ?>
            <?= $item->category ?>

            <?= $item->position ?>

          <ul>
          <ul class="p-postList__hash p-postList__icon">
            <?= $item->dept ?>

          </ul>
        </div>
      </div>
    </a>
  </li>
<?php } ?>
  </div>
  <!-- /.l-event-rec__list -->
  <div class="l-list-prev is-content-justification-center wp-block-buttons">
    <div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="../">一覧に戻る</a></div>
  </div>
</div>


