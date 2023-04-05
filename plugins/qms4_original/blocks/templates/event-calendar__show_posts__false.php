<div
  class="qms4__block__event-calendar js__qms4__block__event-calendar"
  data-show-posts="false"
  data-show-area="<?= $show_area ?>"
  data-show-terms="<?= $show_terms ?>"
  data-taxonomies="<?= join( ',', $taxonomies ) ?>"
  data-query-string="<?= $query_string ?>"
  data-endpoint="<?= home_url( "/wp-json/qms4/v1/event/calendar/{$post_type}/%year%/%month%/" ) ?>"
  data-current="<?= $base_date->format( 'Y-m-d' ) ?>"
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
        <div
          class="qms4__block__event-calendar__body-cell <?= join( ' ', $date_class->format( $calendar_date->date() ) ) ?>"
          data-date="<?= $calendar_date->date()->format( 'Y-m-d' ) ?>"
        >
<?php if ( ! $calendar_date->enable() || empty( $calendar_date->schedules() ) ) { // no event ?>
          <span class="qms4__block__event-calendar__day-title nooo">
            <?= $calendar_date->date()->format( 'j' ) ?>
          </span>
<?php } else {
          $color_code = $event_id = '';
          foreach( $calendar_date->schedules() as $schedule ){
            $event_id   = $schedule->ID;
            $term   = qms4_get_color($event_id);
            break;
          }

  ?>
          <button class="qms4__block__event-calendar__day-title schedule-id-<?= $event_id ?> yess event-calendar-cat-<?= $term->slug ?>" style="background-color: <?= $term->color?>"><?= $calendar_date->date()->format( 'j' ) ?></button>
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

  <div class="qms4__block__event-calendar__display">
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
  <!-- /.qms4__block__event-calendar__display -->
</div>
<!-- /.qms4__block__event-calendar -->
