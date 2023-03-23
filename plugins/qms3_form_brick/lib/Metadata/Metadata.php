<?php
declare(strict_types=1);

namespace QMS3\Brick\Metadata;

use QMS3\Brick\Form\Form;
use QMS3\Brick\Field\Field;


class Metadata
{
    /** @var    array<string,Field> */
    private $fields;

    /** @var    string */
    private $recaptcha_sitekey;

    /**
     * @param    array<string,Field>    $fields
     * @param    string|null            $recaptcha_sitekey
     */
    public function __construct(array $fields, $recaptcha_sitekey)
    {
        $this->fields            = $fields;
        $this->recaptcha_sitekey = $recaptcha_sitekey;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        $metadata = [
            "validation_rules"  => $this->validation_rules(),
            "autokana"          => $this->autokana(),
            "zip2addr"          => $this->zip2addr(),
            "recaptcha_sitekey" => $this->recaptcha_sitekey,
        ];

        return json_encode($metadata, JSON_PRETTY_PRINT);
    }

    // ====================================================================== //

    /**
     * @return    array<string,array>
     */
    public function validation_rules()
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $rule = [];

            if ($field->required) {
                $rule["required"] = true;
            }

            if ($field->type == "email") {
                $rule["email"] = true;
            }

            if ($field->type == "tel") {
                $rule["tel"] = true;
            }

            if ($field->type == "zip") {
                $rule["zip"] = true;
            }

            if ($field->type == "address") {
                $rule["address"] = true;
            }

            $equal_to = $field->ref->equal_to();
            if (!empty($equal_to)) {
                $field_ref = $equal_to[0];
                $target_name = $field_ref->ref_to();
                $rule["equalTo"] = "input[name='{$target_name}']";
            }

            if (!empty($rule)) {
                $name = $field->type == "checkbox"
                    ? "{$field->name}[]"
                    : $field->name;

                $rules[$name] = $rule;
            }
        }

        return $rules;
    }

    /**
     * @return    array[]
     */
    public function autokana()
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $autokana = $field->ref->autokana();

            if (empty($autokana)) { continue; }

            $field_ref = $autokana[0];
            $data = $field_ref->data();

            $rules[$field->name] = [
                "target"   => $field_ref->ref_to(),
                "katakana" => $data["katakana"],
            ];
        }

        return $rules;
    }

    /**
     * @return    array[]
     */
    public function zip2addr()
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $zip2addr = $field->ref->zip2addr();

            if (empty($zip2addr)) { continue; }

            if (count($zip2addr) == 2) {
                list($pref_field_ref, $addr_field_ref) = $zip2addr;
                $rules[$field->name] = [
                    $pref_field_ref->ref_to(),
                    $addr_field_ref->ref_to(),
                ];
            } else if (count($zip2addr) == 1) {
                list($both_field_ref) = $zip2addr;
                $rules[$field->name] = [
                    $both_field_ref->ref_to(),
                ];
            }
        }

        return $rules;
    }
}
