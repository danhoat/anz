<?php
declare(strict_types=1);

namespace QMS3\Brick\HealthCheckWarning;

use QMS3\Brick\Structure\StructureRow;


class ExistsEmptyNameRow
{
    /** @var    int */
    private $index;

    /** @var    StructureRow */
    private $structure_row;

    /**
     * @param    int             $index
     * @param    StructureRow    $structure_row
     */
    public function __construct($index, StructureRow $structure_row)
    {
        $this->index         = $index;
        $this->structure_row = $structure_row;
    }

    public function __toString()
    {
        return $this->warn();
    }

    // ====================================================================== //

    public function warn()
    {
        $block = $this->structure_row->block;
        $label = str_replace("\n", "\\n", $this->structure_row->label);

        return "フォーム項目設定の中に name 属性が空欄になっている行があります。: {$this->index} 行目, \$block = \"{$block}\", \$label = \"{$label}\"";
    }

    /**
     * @return
     */
    public function to_array()
    {
        return [
            "message" => $this->warn(),
            "data" => [
                "index" => $this->index,
                "structure_row" => $this->structure_row->to_array(),
            ],
        ];
    }
}
