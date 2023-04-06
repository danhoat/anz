<?php
 $page_title  ='イベント';
 $css   = '';
 $ymd   = isset($_GET['ymd']) ? $_GET['ymd'] :0;
 if($ymd){

    $date = date_create($ymd);
    $page_title = 'Date: '. $date->format('y年n月j日');
    $css = 'calendar-day-list';
  }
?>
<div class="p-topArea -noimg">
  <div class="p-topArea__body l-container">
    <div class="p-topArea__title c-pageTitle">
      <h1 class="c-pageTitle__main <?= $css?>"><?=$page_title ?></h1>
    </div>
  </div>
</div>
