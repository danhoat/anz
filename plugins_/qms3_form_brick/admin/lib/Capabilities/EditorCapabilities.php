<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Capabilities;


/**
 * 編集者
 * 一覧表示のみできる、新規作成・編集・削除はできない
 */
class EditorCapabilities
{
    /** @var    string */
    private $role = "editor";

    /** @var    array<string,bool> */
    private $capabilities = [
        "create_bricks"           => false,
        "delete_bricks"           => false,
        "delete_others_bricks"    => false,
        "delete_private_bricks"   => false,
        "delete_published_bricks" => false,
        "edit_bricks"             => true,
        "edit_others_bricks"      => false,
        "edit_private_bricks"     => false,
        "edit_published_bricks"   => false,
        "publish_bricks"          => false,
        "read_private_bricks"     => false,
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
