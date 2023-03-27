<?php
declare(strict_types=1);

namespace QMS3\Brick\RefFactory;

use QMS3\Brick\Ref\Ref;
use QMS3\Brick\FieldRef\AutokanaFieldRef;
use QMS3\Brick\FieldRef\EqualToFieldRef;
use QMS3\Brick\FieldRef\Zip2addrFieldRef;
use QMS3\Brick\FieldRefParser\AutokanaFieldRefParser;
use QMS3\Brick\FieldRefParser\EqualToFieldRefParser;
use QMS3\Brick\FieldRefParser\Zip2addrFieldRefParser;


class RefFactory
{
    /**
     * @param     string    $options_str
     * @return    Ref
     */
    public function create($options_str)
    {
        $ref = new Ref();

        $autokana_field_refs = $this->create_autokana($options_str);
        foreach ($autokana_field_refs as $autokana_field_ref) {
            $ref->add_autokana($autokana_field_ref);
        }

        $equal_to_field_refs = $this->create_equal_to($options_str);
        foreach ($equal_to_field_refs as $equal_to_field_ref) {
            $ref->add_equal_to($equal_to_field_ref);
        }

        $zip2addr_field_refs = $this->create_zip2addr($options_str);
        foreach ($zip2addr_field_refs as $zip2addr_field_ref) {
            $ref->add_zip2addr($zip2addr_field_ref);
        }

        return $ref;
    }

    /**
     * @param     string    $options_str
     * @return    AutokanaFieldRef[]
     */
    private function create_autokana($options_str)
    {
        $parser = new AutokanaFieldRefParser();
        return $parser->parse($options_str);
    }

    /**
     * @param     string    $options_str
     * @return    EqualToFieldRef[]
     */
    private function create_equal_to($options_str)
    {
        $parser = new EqualToFieldRefParser();
        return $parser->parse($options_str);
    }

    /**
     * @param     string    $options_str
     * @return    Zip2addrFieldRef[]
     */
    private function create_zip2addr($options_str)
    {
        $parser = new Zip2addrFieldRefParser();
        return $parser->parse($options_str);
    }
}
