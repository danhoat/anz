<?php
declare(strict_types=1);

namespace QMS3\Brick\HealthCheck;

use QMS3\Brick\HealthCheckWarning\ExistsEmptyNameRow;
use QMS3\Brick\HealthCheckWarning\HealthCheckWarningInterface as HealthCheckWarning;
use QMS3\Brick\Structure\StructureRow;


class HealthCheck
{
    /**
     * @param     StructureRow[]    $structure_table
     * @return    HealthCheckWarning[]
     */
    public function check(array $structure_table)
    {
        $warnings = [];

        foreach (array_values($structure_table) as $index => $structure_row) {
            if (trim($structure_row->name) == false) {
                $warnings[] = new ExistsEmptyNameRow($index, $structure_row);
            }

            $index++;
        }

        return $warnings;
    }
}
