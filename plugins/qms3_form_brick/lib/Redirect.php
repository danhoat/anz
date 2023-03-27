<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Enum\Device;
use QMS3\Brick\Form\Form;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Util\DeviceDetector;
use QMS3\Brick\Util\Template;


class Redirect
{
    const TEMPLATES_DIR = __DIR__ . "/../templates";

    /** @var    Param */
    private $param;

    /** @var    DeviceDetector */
    private $device_detector;

    /** @var    Template */
    private $template;

    /**
     * @param    Param    $param
     */
    public function __construct(
        Param          $param,
        DeviceDetector $device_detector = null
    )
    {
        $this->param = $param;

        $this->device_detector = $device_detector
            ? $device_detector
            : new DeviceDetector($_SERVER);

        $this->template = new Template(self::TEMPLATES_DIR);
    }

    /**
     * @param     Form    $form
     * @return    void
     */
    public function handle(Form $form)
    {
        $thanks_path = $this->device_detector->detect()->is(Device::PC)
            ? ($this->param->pc_thanks_path ?: $this->param->thanks_path)
            : ($this->param->sp_thanks_path ?: $this->param->thanks_path);

        if ($this->param->hand_over) {
            echo $this->template->render(
                "hand_over",
                [
                    "thanks_path" => $thanks_path,
                    "fields"      => $form->fields,
                ]
            );
        } else {
            header("Location: {$thanks_path}", true, 303);  // 303 See Other
        }

        exit();
    }
}
