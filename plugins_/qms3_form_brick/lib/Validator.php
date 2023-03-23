<?php
declare(strict_types=1);

namespace QMS3\Brick;


class Validator
{
    public function validate()
    {
        // TODO: もっとちゃんとした実装にする

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            http_response_code(400);  // 400 Bad Request
            exit("Bad Request");
        }
    }
}
