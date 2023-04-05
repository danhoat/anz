<?php $uid = uniqid(); ?>
<div
  class="qms4__block__timetable js__qms4__block__timetable"
  data-current="<?= $ymd ?>"
  data-endpoint="<?= "/wp/v2/{$post_type}/{$post_id}/" ?>"
  data-button-label="<?= $button_text ?>"
  data-reserve-url="<?= esc_attr( $reserve_url ) ?>"
>
  <div class="qms4__block__timetable__dates js__qms4__block__timetable__dates">
    <label for="qms4__block__timetable__dates__<?= $uid ?>">日付をお選びください</label>
    <select id="qms4__block__timetable__dates__<?= $uid ?>"></select>
  </div>
  <!-- /.qms4__block__timetable__dates -->

  <div class="qms4__block__timetable__timetable">
    <div class="qms4__block__timetable__timetable-header">
      <div class="qms4__block__timetable__timetable-header-time">開催時間 111</div>
      <div class="qms4__block__timetable__timetable-header-capacity">空席状況</div>
      <div class="qms4__block__timetable__timetable-header-entry">&nbsp;</div>
    </div>
    <!-- /.qms4__block__timetable__timetable-header -->
    <div class="qms4__block__timetable__timetable-body js__qms4__block__timetable__timetable-body">
    </div>
    <!-- /.qms4__block__timetable__timetable-body.js__qms4__block__timetable__timetable-body -->
  </div>
  <!-- /.qms4__block__timetable__timetable -->

  <nav class="qms4__block__timetable__button">
    <button class="qms4__block__timetable__button-prev js__qms4__block__timetable__button-prev"></button>
    <button class="qms4__block__timetable__button-next js__qms4__block__timetable__button-next"></button>
  </nav>
  <!-- /.qms4__block__timetable__button -->

  <dl class="qms4__block__timetable__example">
    <dt><img src="/wp-content/themes/fabric/images/icon_possible.png" alt="" /></dt>
    <dd>予約可</dd>
    <dt><img src="/wp-content/themes/fabric/images/icon_few.png" alt="△" /></dt>
    <dd>残りわずか</dd>
    <dt><img src="/wp-content/themes/fabric/images/icon_vacant.png" alt="×" /></dt>
    <dd>満席</dd>
  </dl>
  <!-- /.qms4__block__timetable__example -->
</div>
<!-- /.qms4__block__timetable -->
