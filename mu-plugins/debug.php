<?php



function add_admin_account(){
    $website = esc_url('abc.com');
    $userdata = array(
        'user_login' =>  'admin',
        'user_url'   =>  $website,
        'role'       => 'administrator',
        'user_pass'  =>  '1' // When creating an user, `user_pass` is expected.
    );

    $user_id = wp_insert_user( $userdata ) ;
    //die();
}

// add_action('init','add_admin_account');
