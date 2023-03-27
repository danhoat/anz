<div class="brick-form__block form__block-<?= $this->slugify($block->block) ?>">
<?php foreach ($block as $row) { ?>
  <?= $row->render('hidden') ?>

<?php } ?>
</div>
<!-- /.brick-form__block -->
