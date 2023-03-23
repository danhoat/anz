<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\DuplicatePost;


class RestrictOfForms
{
    public function __construct()
    {
        add_filter("duplicate_post_enabled_post_types", [$this, "restrict_of_forms"]);
    }

    /**
     * @param     string[]    $enabled_post_types
     * @return    string[]
     */
    public function restrict_of_forms(array $enabled_post_types)
    {
        if (current_user_can("create_bricks")) { return $enabled_post_types; }

        $new_enabled_post_types = [];
        foreach ($enabled_post_types as $enabled_post_type) {
            if ($enabled_post_type === "brick") { continue; }
            $new_enabled_post_types[] = $enabled_post_type;
        }

        return $new_enabled_post_types;
    }
}
