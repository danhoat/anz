<div class="qms4__event-structure">
  <dl>
    <dt>日程</dt>
    <dd><?= wp_date( 'Y年n月j日（D）', $event_date->getTimestamp() ) ?></dd>
  </dl>
  <dl>
    <dt>関連イベント</dt>
    <dd>
      <a href="<?= admin_url( "/post.php?post={$parent_event_id}&action=edit" ) ?>" target="_blank">
        <?= get_the_title( $parent_event_id ) ?>

      </a>
    </dd>
  </dl>
</div>
<!-- /.qms4__event-structure -->

<style>
.qms4__event-structure dl {
  display: flex;
}

.qms4__event-structure dt {
  flex-basis: 120px;
  font-weight: 700;
}
.qms4__event-structure dt::after {
  content: ":";
  margin-left: .2em;
}

.qms4__event-structure dd {
  flex-grow: 1;
  margin: 0;
}
</style>
