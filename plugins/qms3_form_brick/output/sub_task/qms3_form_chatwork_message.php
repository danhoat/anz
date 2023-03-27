<?php

use QMS3\Brick\SubTask\ChatworkMessage;

/**
 * @since    1.5.2
 *
 * @param     string    $token
 * @param     int       $room_id
 * @param     mixed     $message
 * @param     int[]     $to_ids
 * @return    ChatworkMessage
 */
function qms3_form_chatwork_message($token, $room_id, $message, array $to_ids = [])
{
    return new ChatworkMessage($token, $room_id, $message, $to_ids);
}
