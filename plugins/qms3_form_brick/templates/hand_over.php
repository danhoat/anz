<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>
<body onload="document.forms[0].submit()" style="display:none">
<form action="<?= $thanks_path ?>" method="POST">
<?php foreach ($fields as $field) { ?>
  <?= $field->render('hidden') ?>
<?php } ?>
</form>
</body>
</html>
