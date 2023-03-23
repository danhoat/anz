<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Capabilities;


/**
 * 寄稿者 権限グループ
 * 一切の権限を持っていない
 */
class ContributorCapabilities
{
    /** @var    string */
    private $role = "contributor";

    /** @var    array<string,bool> */
    private $capabilities = [
        "create_bricks"           => false,
        "delete_bricks"           => false,
        "delete_others_bricks"    => false,
        "delete_private_bricks"   => false,
        "delete_published_bricks" => false,
        "edit_bricks"             => false,
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
