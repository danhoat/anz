<ol class="brick-flow brick-flow--step-<?= count($steps) ?>">
<?php foreach ($steps as $step => $label) { ?>
  <li class="brick-flow__step <?= $this->is_current($step) ? 'brick-flow__step--current' : '' ?>">
    <span><?= $label ?></span>
  </li>
<?php } ?>
</ol>
