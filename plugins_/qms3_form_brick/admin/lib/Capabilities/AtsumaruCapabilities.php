<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Capabilities;


/**
 * あつまる従業員 権限グループ
 * 一覧表示・新規作成・編集はできる、削除はできない
 */
class AtsumaruCapabilities
{
    /** @var    string */
    private $role = "atsumaru";

    /** @var    array<string,bool> */
    private $capabilities = [
        "create_bricks"           => true,
        "delete_bricks"           => false,
        "delete_others_bricks"    => false,
        "delete_private_bricks"   => false,
        "delete_published_bricks" => false,
        "edit_bricks"             => true,
        "edit_others_bricks"      => true,
        "edit_private_bricks"     => true,
        "edit_published_bricks"   => true,
        "publish_bricks"          => true,
        "read_private_bricks"     => true,
    ];

    public function set()
    {
        $role = get_role($this->role);

        if (!$role) { return; }

        foreach ($this->capabilities as $capability => $active) {
            if ($active) {
                $role->add_cap($capability);
            } else {
                $role->remove_cap($capability);
            }
        }
    }

    public function clear()
    {
        $role = get_role($this->role);

        if (!$role) { return; }

        foreach ($this->capabilities as $capability => $_) {
            $role->remove_cap($capability);
        }
    }
}
