<?php
if ( is_active_sidebar( 'sidebar-news' ) ) {
  echo '<!-- ### start sidebar-store ### -->' . "\n";
  dynamic_sidebar( 'sidebar-news' );
  echo '<!-- ### end sidebar-news ### -->' . "\n";
}

