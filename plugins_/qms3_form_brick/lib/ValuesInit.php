<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;
use QMS3\Brick\ValueDecoderFactory\ValueDecoderFactory;


/**
 * @since    1.5.2
 */
class ValuesInit
{
    /**
     * @param     Structure              $structure
     * @param     array<string,mixed>    $default_values
     * @return    Values
     */
    public function init(Structure $structure, array $default_values)
    {
        $factory = new ValueDecoderFactory();
        $decoder = $factory->create($structure);

        $request = new ServerRequest(
            isset($_GET)   ? $_GET   : [],
            isset($_POST)  ? $_POST  : [],
            isset($_FILES) ? $_FILES : []
        );

        return $decoder->decode($request, $default_values);
    }
}
