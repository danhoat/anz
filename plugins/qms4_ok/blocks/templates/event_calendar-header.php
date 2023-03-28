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