<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader;


class PhpLoader
{
    /**
     * @param     string    $filepath
     * @return    array<int,array>
     */
    public function load($filepath)
    {
        return require($filepath);
    }
}
