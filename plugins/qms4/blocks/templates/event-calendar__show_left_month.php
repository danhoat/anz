
<div
  class="qms4__block__event-calendar js__qms4__block__event-calendar qms4__block__event-calendar__custom"
  data-show-posts="false"
  data-show-area="<?= $show_area ?>"
  data-show-terms="<?= $show_terms ?>"
  data-taxonomies="<?= join( ',', $taxonomies ) ?>"
  data-query-string="<?= $query_string ?>"
  data-endpoint="<?= home_url( "/wp-json/qms4/v1/event/calendar/{$post_type}/%year%/%month%/" ) ?>"
  data-current="<?= $base_date->format( 'Y-m-d' ) ?>">

<div class="qms4__block__event-calendar__container   ">

    <div class="qms4__block__event-calendar__month-header">
      <button class="qms4__block__event-calendar__button-prev js__qms4__block__event-calendar__button-prev">
        前の月
      </button>

      <div class="qms4__block__event-calendar__month-title">
        <div class="qms4__block__event-calendar__month-title__year js__qms4__block__event-calendar__month-title__year"><?= $base_date->format( 'Y' ) ?></div>
        <div class="qms4__block__event-calendar__month-title__month js__qms4__block__event-calendar__month-title__month"><?= $base_date->format( 'n' ) ?></div>
        <div class="qms4__block__event-calendar__month-title__month-name js__qms4__block__event-calendar__month-title__month-name"><?= $base_date->format( 'F' ) ?></div>
      </div>
      <!-- /.qms4__block__event-calendar__month-title -->


    </div>
    <!-- /.qms4__block__event-calendar__month-header -->

    <div class="qms4__block__event-calendar__calendar">
      <div class="qms4__block__event-calendar__calendar-header">
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--mon">月</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--tue">火</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--wed">水</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--thu">木</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--fri">金</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--sat">土</div>
        <div class="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--sun">日</div>
      </div>
      <!-- /.qms4__block__event-calendar__calendar-header -->

      <div class="qms4__block__event-calendar__calendar-body js__qms4__block__event-calendar__calendar-body">
<?php foreach ( $calendar_month as $calendar_date ) { ?>
        <div
          class="qms4__block__event-calendar__body-cell <?= join( ' ', $date_class->format( $calendar_date->date() ) ) ?>"
          data-date="<?= $calendar_date->date()->format( 'Y-m-d' ) ?>"
        >
<?php if ( ! $calendar_date->enable() || empty( $calendar_date->schedules() ) ) { ?>
          <span class="qms4__block__event-calendar__day-title">
            <?= $calendar_date->date()->format( 'j' ) ?>
          </span>
<?php } else {
  $color_code = $event_id = '';
  $term = (object) array('color'=>'','slug' => '');

  foreach( $calendar_date->schedules() as $schedule ){

    $event_id   = $schedule->ID;
    $term   = qms4_get_color($event_id);
    if($term){  break; }
  }
  $color = isset($term->color) ? $term->color : '';
  //$link = get_post_type_archive_link('fair');
  $link = get_post_type_archive_link('fair');
  $link = add_query_arg( array(
    'ymd' => $calendar_date->date()->format( 'Y-m-d' ),
  ), $link);

  ?>

   <a href="<?php echo $link;?>" class="qms4__block__event-calendar__day-title " style= "background-color:<?= $color ?>"> <?= $calendar_date->date()->format( 'j' ) ?></a>

<?php } ?>
        </div>
        <!-- /.qms4__block__event-calendar__body-cel -->
<?php } ?>


      </div>
      <!-- /.qms4__block__event-calendar__calendar-body.js__qms4__block__event-calendar__calendar-body -->
    </div>
    <!-- /.qms4__block__event-calendar__calendar -->

    <div class="qms4__block__event-calendar__month-footer">
      <button class="qms4__block__event-calendar__button-prev js__qms4__block__event-calendar__button-prev">
        前の月
      </button>

      <!-- <button class="qms4__block__event-calendar__button-next js__qms4__block__event-calendar__button-next">
        次の月
      </button> -->
    </div>
    <!-- /.qms4__block__event-calendar__month-footer -->
  </div>
  <!-- /.qms4__block__event-calendar__container -->