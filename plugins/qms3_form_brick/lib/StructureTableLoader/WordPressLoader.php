<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader;

use QMS3\BrickAdmin\Settings\FormStructureSettings;


/**
 * TODO: このクラスの機能、 Repository クラスとして管理画面側の処理ともまとめる
 */
class WordPressLoader
{
    /**
     * @param     string    $slug
     * @return    array<int,array>
     */
    public function load($slug)
    {
        $form_structure_settings = FormStructureSettings::get($slug);
        return $form_structure_settings->form_structure;
    }
}
