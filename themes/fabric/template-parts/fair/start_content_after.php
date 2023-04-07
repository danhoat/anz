<div class="l-search">

<?php
 $css   = '';
 $ymd   = isset($_GET['ymd']) ? $_GET['ymd'] :0;
 if( is_archive() && $ymd && is_valid_date($ymd)){

    $date = date_create($ymd);
    // $page_title = $date->format('Y年n月j日'); //2023年4月21日
    echo '<div class="calendar-day-title">'.$date->format('Y年n月j日').'</div>';
  }

  ?>

<!-- イベント_タクソノミー -->
<?= qms4_site_part( 859 ) ?>


 <!-- refer  qms4/blocks/templates/term-list.php!-->
</div>
