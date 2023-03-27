<div class="brick-form__block form__block-<?= $this->slugify($block->block) ?>">
<?php foreach ($block as $row) { ?>
  <?= $row->render('input') ?>

<?php } ?>
</div>
<!-- /.brick-form__block -->
