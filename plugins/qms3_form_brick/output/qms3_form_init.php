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
    $values = $values_init->init( $structure, $param->default );

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
