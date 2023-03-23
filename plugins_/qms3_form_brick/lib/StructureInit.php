<?php
declare(strict_types=1);

namespace QMS3\Brick;

use InvalidArgumentException;
use RuntimeException;
use JsonSchema\Validator as SchemaValidator;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\StructureTableLoader\StructureTableLoader;


/**
 * @since    1.5.2
 */
class StructureInit
{
    /** @var    string */
    private $form_type;

    /** @var    Param */
    private $param;

    /**
     * @param     string       $form_type
     * @param     Param        $param
     * @return    Structure
     */
    public function __construct($form_type, Param $param)
    {
        $this->form_type = $form_type;
        $this->param     = $param;
    }

    /**
     * @return    Structure
     */
    public function init()
    {
        $loader = new StructureTableLoader();
        $structure_table = $loader->load($this->form_type, $this->param);
        $overwrite_options = $this->param->options;

        $this->validate($structure_table);

        $structure_rows = [];
        foreach ($structure_table as $row) {
            $name = $row[ 'name' ];
            if ( ! empty( $name ) && ! empty( $overwrite_options[ $name ] ) ) {
                $row[ 'options' ] = $overwrite_options[ $name ];
            }

            $structure_rows[] = new StructureRow($row);
        }

        $names = [];
        foreach ($structure_rows as $structure_row) {
            $name = $structure_row->name;

            if (empty($name)) {
                throw new InvalidArgumentException("空文字の name 属性は許されません。空文字の name 属性が許容されるのは type が 'title' のときのみです");
            }

            $names[] = $name;
        }

        if (count($names) != count(array_unique($names))) {
            throw new InvalidArgumentException("重複した name 属性があります。各行の name 属性には一意な文字列を設定してください");
        }


        return new Structure(array_combine($names, $structure_rows));
    }

    // ====================================================================== //

    /**
     * @param     array<int,array>    $structure_table
     * @return    void
     * @throws    RuntimeException
     */
    private function validate(array $structure_table)
    {
        if (empty($structure_table)) {
            throw new \RuntimeException("フォーム項目設定は空であってはいけません。");
        }

        $schema = realpath(__DIR__ . "/../schema/form_structure_table.schema.json");

        $validator = new SchemaValidator();
        $validator->validate(
            $structure_table,
            (object) [ '$ref' => "file://{$schema}" ]
        );

        if (!$validator->isValid()) {
            $messages = [];
            foreach ($validator->getErrors() as $error) {
                $messages[] = sprintf("[%s] %s", $error["property"], $error["message"]);
            }

            throw new RuntimeException(join("\n", $messages));
        }
    }
}
