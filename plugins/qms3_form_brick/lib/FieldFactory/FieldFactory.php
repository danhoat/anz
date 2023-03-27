<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldFactory;

use QMS3\Brick\Field\Field;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\Util\PlainTextSanitizer;
use QMS3\Brick\Values\ValuesInterface;

use QMS3\Brick\Field\AddressField;
use QMS3\Brick\Field\CheckboxField;
use QMS3\Brick\Field\DatepickerField;
use QMS3\Brick\Field\EmailField;
use QMS3\Brick\Field\FileField;
use QMS3\Brick\Field\HiddenField;
use QMS3\Brick\Field\PasswordField;
use QMS3\Brick\Field\PrefField;
use QMS3\Brick\Field\RadioField;
use QMS3\Brick\Field\SelectField;
use QMS3\Brick\Field\TelField;
use QMS3\Brick\Field\TextareaField;
use QMS3\Brick\Field\TitleField;
use QMS3\Brick\Field\TextField;
use QMS3\Brick\Field\ZipField;


class FieldFactory
{
    /**
     * @param     StructureRow    $structure_row
     * @return    Field
     */
    public function create(
        StructureRow    $structure_row,
        ValuesInterface $values,
        Step            $step
    )
    {
        $sanitizer = new PlainTextSanitizer();

        switch ($structure_row->type) {
            case "address"       : return new AddressField($structure_row, $values, $step, $sanitizer);
            case "checkbox"      : return new CheckboxField($structure_row, $values, $step, $sanitizer);
            case "datepicker"    : return new DatepickerField($structure_row, $values, $step, $sanitizer);
            case "email"         : return new EmailField($structure_row, $values, $step, $sanitizer);
            case "file"          : return new FileField($structure_row, $values, $step, $sanitizer);
            case "hidden"        : return new HiddenField($structure_row, $values, $step, $sanitizer);
            case "password"      : return new PasswordField($structure_row, $values, $step, $sanitizer);
            case "pref"          : return new PrefField($structure_row, $values, $step, $sanitizer);
            case "radio"         : return new RadioField($structure_row, $values, $step, $sanitizer);
            case "select"        : return new SelectField($structure_row, $values, $step, $sanitizer);
            case "tel"           : return new TelField($structure_row, $values, $step, $sanitizer);
            case "textarea"      : return new TextareaField($structure_row, $values, $step, $sanitizer);
            case "title"         : return new TitleField($structure_row, $values, $step, $sanitizer);
            case "zip"           : return new ZipField($structure_row, $values, $step, $sanitizer);

            case "text":
            default:
                return new TextField($structure_row, $values, $step, $sanitizer);
        }
    }
}
