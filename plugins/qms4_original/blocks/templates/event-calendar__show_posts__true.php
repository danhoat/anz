<div
  class="qms4__block__event-calendar"
  data-show-posts="true"
>
  <div className="qms4__block__event-calendar__container">
    <div class="qms4__block__event-calendar__month-header">
      <button class="qms4__block__event-calendar__button-prev">前の月</button>

      <div class="qms4__block__event-calendar__month-title"><?= $base_date->format( 'n' ) ?></div>

      <button class="qms4__block__event-calendar__button-next">次の月</button>
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

      <div class="qms4__block__event-calendar__calendar-body">
<?php foreach ( $calendar_month as $calendar_date ) { ?>
        <div class="qms4__block__event-calendar__body-cell <?= join( ' ', $date_class->format( $calendar_date->date() ) ) ?>">
          <div class="qms4__block__event-calendar__day-title 111"><?= $calendar_date->date()->format( 'j' ) ?></div>

          <div class="qms4__block__event-calendar__schedules-container">
<?php foreach ( $calendar_date->schedules() as $schedule ) { ?>
            <a
              href="<?= get_permalink( $schedule->id ) ?>"
              target="<?= $link_target ?>"
            >
              <?= $schedule->title ?>

            </a>
<?php } ?>
          </div>
          <!-- /.qms4__block__event-calendar__schedules-container -->
        </div>
        <!-- /.qms4__block__event-calendar__body-cel -->
<?php } ?>
      </div>
      <!-- /.qms4__block__event-calendar__calendar-body -->
    </div>
    <!-- /.qms4__block__event-calendar__calendar -->

    <div class="qms4__block__event-calendar__month-footer">
      <button class="qms4__block__event-calendar__button-prev">前の月</button>

      <button class="qms4__block__event-calendar__button-next">次の月</button>
    </div>
    <!-- /.qms4__block__event-calendar__month-footer -->
  </div>
  <!-- /.qms4__block__event-calendar__container -->
</div>
<!-- /.qms4__block__event-calendar.qms4__block__event-calendar--show-posts-true -->
