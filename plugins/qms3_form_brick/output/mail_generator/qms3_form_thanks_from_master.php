<?php

use QMS3\Brick\MailGenerator\ThanksMailFromMasterGenerator as MasterGenerator;


/**
 * @param    string    $name
 * @param    array<string,string>    $map
 * @return    MasterGenerator
 */
function qms3_form_thanks_from_master( $name, array $map = array() )
{
    return new MasterGenerator( $name, $map );
}
