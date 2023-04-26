<?php $archive_link = wp_is_mobile() ? $archive_link: '';
$date = new DateTime("+2 months");
$next_2month =  $date->format("Y-m-d");
 ?>
<div
  class="qms4__block__event-calendar js__qms4__block__event-calendar js_calendar_right_list_event"
  data-show-posts="false"
  data-set-style ="1month"
  data-set-post-type =<?= $post_type?>
  data-set-archive-link = <?= $archive_link ?>
  data-show-area="<?= $show_area ?>"
  data-show-terms="<?= $show_terms ?>"
  data-taxonomies="<?= join( ',', $taxonomies ) ?>"
  data-query-string="<?= $query_string ?>"
  data-endpoint="<?= home_url( "/wp-json/qms4/v1/event/calendar/{$post_type}/%year%/%month%/" ) ?>"
  data-current="<?= $base_date->format( 'Y-m-d' ) ?>"
  data-next-2month="<?= $next_2month  ?>"
>
  <div class="qms4__block__event-calendar__container">
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

      <button class="qms4__block__event-calendar__button-next js__qms4__block__event-calendar__button-next">
        次の月
      </button>
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
    <?php
     foreach( $calendar_date->schedules() as $schedule ){

        $event_id   = $schedule->ID;
        $term       = qms4_get_color($event_id);
        if($term){  break; }
    }
    $color = isset($term->color) ? $term->color : '';
    ?>
        <div
          class="qms4__block__event-calendar__body-cell <?= join( ' ', $date_class->format( $calendar_date->date() ) ) ?>"
          data-date="<?= $calendar_date->date()->format( 'Y-m-d' ) ?>"
        >
<?php if ( ! $calendar_date->enable() || empty( $calendar_date->schedules() ) ) { ?>
          <span class="qms4__block__event-calendar__day-title">
            <?= $calendar_date->date()->format( 'j' ) ?>
          </span>
<?php } else  if( wp_is_mobile() ) { ?>


   <a href="<?= $archive_link?>?ymd=<?= $calendar_date->date()->format( 'Y-m-d' ) ?>" class="qms4__block__event-calendar__day-title" style ="background-color: <?= $color;?>" ><?= $calendar_date->date()->format( 'j' ) ?></a>


<?php }  else { ?>
   <button class="qms4__block__event-calendar__day-title" style ="background-color: <?= $color;?>" ><?= $calendar_date->date()->format( 'j' ) ?></button>

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

      <button class="qms4__block__event-calendar__button-next js__qms4__block__event-calendar__button-next">
        次の月
      </button>
    </div>
    <!-- /.qms4__block__event-calendar__month-footer -->
  </div>
  <!-- /.qms4__block__event-calendar__container -->

  <?php if( ! wp_is_mobile() ){ ?>
  <div class="qms4__block__event-calendar__display"> <!-- list post !-->
    <div class="qms4__block__event-calendar__display-inner">
      <div class="qms4__block__event-calendar__display-header js__qms4__block__event-calendar__display-header">
        <?php if ( ! empty( $recent_enable_date ) ) { ?>
          <?= wp_date( 'n月j日（D）', $recent_enable_date->date()->getTimestamp() ) ?>のイベント
        <?php } ?>
      </div>
      <!-- /.qms4__block__event-calendar__display-header -->
      <div class="qms4__block__event-calendar__display-list js__qms4__block__event-calendar__display-list">
        <?php if ( ! empty( $recent_enable_date ) ) { ?>
          <?php foreach ( $recent_enable_date->schedules() as $schedule ) { ?>
            <div class="qms4__block__event-calendar__display-list-item">
              <a href="<?= $schedule->permalink ?>?ymd=<?= $schedule->date( 'Ymd' ) ?>">
                <div class="qms4__block__event-calendar__display-list-item__thumbnail">
                  <?= $schedule->img ?>
                </div>
                <div class="qms4__block__event-calendar__display-list-item__inner">
                  <div class="qms4__block__event-calendar__display-list-item-title">
                    <?= $schedule->title ?>
                  </div>
                  <!-- /.qms4__block__event-calendar__display-list-item-title -->

                  <?php if ( $show_area && $schedule->area ) { ?>
                    <ul class="qms4__block__event-calendar__display-list-item__icons">
                      <li class="qms4__block__event-calendar__display-list-item__icon">
                        <?= $schedule->area->title ?>
                      </li>
                    </ul>
                  <?php } ?>

                  <?php if ( $show_terms ) { ?>
                    <?php foreach ( $taxonomies as $taxonomy ) { ?>
                      <ul class="qms4__block__event-calendar__display-list-item__icons">
                        <?php foreach ( $schedule->event->$taxonomy as $term ) { ?>
                          <li
                            class="qms4__block__event-calendar__display-list-item__icon"
                            <?php if ( $term->color ) { ?>
                              style="border-color:<?= $term->color ?>;background-color:<?= $term->color ?>"
                            <?php } ?>
                          >
                            <?= $term->name ?>
                          </li>
                        <?php } ?>
                      </ul>
                    <?php } ?>
                  <?php } ?>
                </div>
                <!-- /.qms4__block__event-calendar__display-list-item__inner -->
              </a>
            </div>
            <!-- /.qms4__block__event-calendar__display-list-item -->
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <!-- /.qms4__block__event-calendar__display-inner -->
  </div>
<?php  }?>
  <!-- /.qms4__block__event-calendar__display -->
</div>
<!-- /.qms4__block__event-calendar -->
