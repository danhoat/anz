<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\ValueContainerInterface as ValueContainer;


interface ValueDecoderInterface
{
    /**
     * @param     ServerRequest          $request
     * @param     array<string,mixed>    $param_values
     * @return    ValueContainer
     */
    public function decode(ServerRequest $request, array $param_values);
}
