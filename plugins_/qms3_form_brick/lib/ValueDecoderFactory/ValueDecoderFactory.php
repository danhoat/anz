<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoderFactory;

use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ValueDecoder\ValueDecoder;
use QMS3\Brick\ValueDecoder\CheckboxValueDecoder;
use QMS3\Brick\ValueDecoder\DatepickerValueDecoder;
use QMS3\Brick\ValueDecoder\FileValueDecoder;
use QMS3\Brick\ValueDecoder\RadioValueDecoder;
use QMS3\Brick\ValueDecoder\SelectValueDecoder;
use QMS3\Brick\ValueDecoder\TextValueDecoder;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;


class ValueDecoderFactory
{
    /**
     * @param     Structure              $structure
     * @return    ValueDecoder
     */
    public function create(Structure $structure)
    {
        $decoders = [];
        foreach ($structure as $structure_row) {
            $name        = $structure_row->name;
            $decoder = $this->init_decoder($structure_row);

            if (!$decoder) { continue; }

            $decoders[$name] = $decoder;
        }

        return new ValueDecoder($decoders);
    }

    /**
     * @param     StructureRow    $structure_row
     * @return    ValueDecoderInterface|null
     */
    private function init_decoder(StructureRow $structure_row)
    {
        switch ($structure_row->type) {
            case "checkbox"      : return new CheckboxValueDecoder($structure_row);
            case "datepicker"    : return new DatepickerValueDecoder($structure_row);
            case "file"          : return new FileValueDecoder($structure_row);
            case "radio"         : return new RadioValueDecoder($structure_row);
            case "select"        : return new SelectValueDecoder($structure_row);
            case "title"         : return null;

            case "text":
            default:
                return new TextValueDecoder($structure_row);
        }
    }
}
