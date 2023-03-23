<?php
declare(strict_types=1);

namespace QMS3\Brick\Field;

use QMS3\Brick\Enum\UploadStatus;
use QMS3\Brick\Field\Field;
use QMS3\Brick\Ref\Ref;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\Values\ValuesInterface as Values;
use QMS3\Brick\Util\PlainTextSanitizer;
use QMS3\Brick\Util\Template;


/**
 * @property-read    string    $block
 * @property-read    string    $label
 * @property-read    string    $type
 * @property-read    string    $input_type
 * @property-read    string    $name
 * @property-read    mixed     $value
 * @property-read    string    $prepend
 * @property-read    string    $append
 * @property-read    string    $header_notice
 * @property-read    string    $body_notice
 * @property-read    Ref       $ref
 * @property-read    string    $placeholder
 * @property-read    bool      $for_bcc
 * @property-read    bool      $thanks_to
 * @property-read    bool      $required
 * @property-read    string    $attributes
 */
class FileField extends Field
{
    const TEMPLATES_DIR = __DIR__ . "/../../templates/field";

    /** @var    StructureRow */
    protected $structure_row;

    /** @var    Values */
    protected $values;

    /** @var    Template */
    protected $template;

    /** @var    Step */
    protected $step;

    /** @var    PlainTextSanitizer */
    protected $sanitizer;

    public function __construct(
        StructureRow       $structure_row,
        Values             $values,
        Step               $step,
        PlainTextSanitizer $sanitizer
    )
    {
        $this->structure_row = $structure_row;
        $this->values        = $values;
        $this->step          = $step;
        $this->sanitizer     = $sanitizer;

        $this->template = new Template(self::TEMPLATES_DIR);
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function render_input()
    {
        return $this->template->render(
            "file.input",
            [
                "field" => $this,
                "accept" => defined( 'QMS3_FORM_UPLOAD_ACCEPT' )
                    ? QMS3_FORM_UPLOAD_ACCEPT
                    : 'image/*',
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_confirm()
    {
        if ($this->value->status->is(UploadStatus::OK)) {
            $template_name = "file.confirm";
        } else if ($this->value->status->is(UploadStatus::EMPTY)) {
            $template_name = "file.confirm.empty";
        } else if ($this->value->status->is(UploadStatus::OVERSIZE)) {
            $template_name = "file.confirm.oversize";
        } else if ($this->value->status->is(UploadStatus::RUNTIME_ERROR)) {
            $template_name = "file.confirm.runtime_error";
        } else if ($this->value->status->is(UploadStatus::UNKNOWN_ERROR)) {
            $template_name = "file.confirm.unknown_error";
        } else if ($this->value->status->is(UploadStatus::UNSUPPORTED_FILE)) {
            $template_name = "file.confirm.unsupported_file";
        }

        return $this->template->render(
            $template_name,
            [
                "field"  => $this,
                "hidden" => $this->render_hidden(),
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_result()
    {
        if ($this->value->status->is(UploadStatus::OK)) {
            $template_name = "file.confirm";
        } else if ($this->value->status->is(UploadStatus::EMPTY)) {
            $template_name = "file.confirm.empty";
        } else if ($this->value->status->is(UploadStatus::OVERSIZE)) {
            $template_name = "file.confirm.oversize";
        } else if ($this->value->status->is(UploadStatus::RUNTIME_ERROR)) {
            $template_name = "file.confirm.runtime_error";
        } else if ($this->value->status->is(UploadStatus::UNKNOWN_ERROR)) {
            $template_name = "file.confirm.unknown_error";
        } else if ($this->value->status->is(UploadStatus::UNSUPPORTED_FILE)) {
            $template_name = "file.confirm.unsupported_file";
        }

        return $this->template->render(
            $template_name,
            [
                "field"  => $this,
                "hidden" => null,
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_hidden()
    {
        return trim("
<input type=\"hidden\" name=\"{$this->name}[name]\" value=\"{$this->value->filename}\">
<input type=\"hidden\" name=\"{$this->name}[type]\" value=\"{$this->value->mime_type}\">
<input type=\"hidden\" name=\"{$this->name}[tmp_name]\" value=\"\">
<input type=\"hidden\" name=\"{$this->name}[error]\" value=\"{$this->value->error_no}\">
<input type=\"hidden\" name=\"{$this->name}[size]\" value=\"{$this->value->filesize}\">
<input type=\"hidden\" name=\"{$this->name}[url]\" value=\"{$this->value->url}\">
        ");
    }

    /**
     * @return    string
     */
    protected function render_plain()
    {
        if (!$this->value->status->is(UploadStatus::OK)) { return ""; }

        $texts = [
            $this->prepend,
            $this->value,
            $this->append,
        ];

        return join(" ", array_filter($texts));
    }
}
