<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;
use QMS3\Brick\Values\Values;


class ValueDecoder
{
    /** @var    array<string,ValueDecoderInterface> */
    private $decoders;

    /**
     * @param    array<string,ValueDecoderInterface>    $decoders
     */
    public function __construct(array $decoders)
    {
        $this->decoders = $decoders;
    }

    /**
     * @param     ServerRequest          $request
     * @param     array<string,mixed>    $param_values
     * @return    Values
     */
    public function decode(ServerRequest $request, array $param_values)
    {
        $values = [];
        foreach ($this->decoders as $name => $decoder) {
            $values[$name] = $decoder->decode($request, $param_values);
        }

        return new Values($values);
    }
}
