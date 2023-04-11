<?php
declare(strict_types=1);

use QMS3\Brick\FieldsInit;
use QMS3\Brick\PreProcessInit;
use QMS3\Brick\SubTaskInit;
use QMS3\Brick\Redirect;
use QMS3\Brick\SendMails;
use QMS3\Brick\StructureInit;
use QMS3\Brick\Validator;
use QMS3\Brick\ValuesInit;
use QMS3\Brick\Form\Form;
use QMS3\Brick\Logger\MailLogger;
use QMS3\Brick\Metadata\Metadata;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\FormTypeNormalizer;
use QMS3\Brick\Util\StepDetector;


/**
 * @param     string     $form_type
 * @param     mixed[]    $param
 * @return    Form
 */
function qms3_form_init( $form_type, array $param = array() )
{
    $normalizer = new FormTypeNormalizer();
    $form_type = $normalizer->normalize( $form_type );

    if ( function_exists( 'apply_filters' ) ) {
        $param = apply_filters( 'qms3_form_param', $param, $form_type );
    }

    $param = new Param( $form_type, $param );

    MailLogger::setup( 'form', $param );

    $detector = new StepDetector( $param );
    $step = $detector->detect();

    $structure_init = new StructureInit( $form_type, $param );
    $structure = $structure_init->init();

    $values_init = new ValuesInit();
   // var_dump($param->default);
    $values = $values_init->init( $structure, $param->default );
    // echo '<pre>';
    // var_dump($values);
    // echo '</pre>';
    $pre_process_init = new PreProcessInit( $param->pre_processes, $param );
    $pre_process = $pre_process_init->init();
    list( $structure, $values ) = $pre_process->process(
        $structure,
        $values,
        $form_type,
        $param,
        $step
    );

    $sub_task_init = new SubTaskInit( $param->sub_tasks, $param );
    $sub_task = $sub_task_init->init();
    $sub_task->process(
        $structure,
        $values,
        $form_type,
        $param,
        $step
    );

    $fields_init = new FieldsInit();
    $fields = $fields_init->init( $structure, $values, $step );
    $metadata = new Metadata( $fields, $param->recaptcha_sitekey );

    $form = new Form( $param->form_name, $fields, $values, $metadata, $step );

    if ( $step->is( Step::SUBMIT ) ) {
        $validator = new Validator();
        $validator->validate();

        $send_mails = new SendMails( $form_type, $param );
        $send_mails->send_mails( $form );

        $redirect = new Redirect( $param );
        $redirect->handle( $form );
    }

    return $form;
}

function qms4_list_schedules_of_fair($fair_id){


    global $wpdb;
            $sql =  $wpdb->prepare("
               SELECT SQL_CALC_FOUND_ROWS mt.meta_value as str_day
               FROM $wpdb->posts p
                INNER JOIN $wpdb->postmeta m ON ( p.ID = m.post_id )
                INNER JOIN $wpdb->postmeta AS mt1 ON ( p.ID = mt1.post_id ) WHERE 1=1 AND
                    ( ( m.meta_key = %s AND( mt1.meta_key = %s ) )
                    AND p.post_type = %s AND p.post_status = 'publish' AND m1.meta_value = %d
                    ",
                    'qms4__event_date',
                    'qms4__parent_event_id',
                    'fair__schedule',
                    $fair_id
                 );
                    echo $sql;

    $result =$wpdb->get_results($sql, ARRAY_A);

    foreach($result as $r){
        echo '<pre>';
        var_dump($r);
        echo '</pre>';
    }

}
function dev_debug(){
    qms4_list_schedules_of_fair(2672);
}

//add_action('wp_footer','dev_debug');
