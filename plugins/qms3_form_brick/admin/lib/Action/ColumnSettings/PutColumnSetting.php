<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;


class PutColumnSetting
{
    /** @var    string */
    private $dirname;

    /**
     * @param    string    $dirname
     */
    public function __construct( $dirname )
    {
        $this->dirname = '/' . trim( $dirname, '/' );
    }

    public function __invoke()
    {
        $column_settings_json = file_get_contents( __DIR__ . '/../../../assets/column_settings.json' );
        $column_settings = json_decode( $column_settings_json, /* $assoc = */ true );

        $content = "<?php return json_decode('{$column_settings_json}', /* \$assoc = */ true);";

        $upload_dir = wp_upload_dir();
        $dirpath = $upload_dir[ 'basedir' ] . $this->dirname;

        $filename = "{$column_settings['id']}.php";
        $filepath = "{$dirpath}/{$filename}";

        if ( ! file_exists( $dirpath ) ) { mkdir( $dirpath, 0777, true ); }

        file_put_contents($filepath, $content);
    }
}
