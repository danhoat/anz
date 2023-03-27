<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;

use AC\ListScreenRepository\Rule;
use AC\ListScreenRepository\Rules;
use AC\ListScreenRepository\Storage\ListScreenRepository;
use AC\ListScreenRepository\Storage\ListScreenRepositoryFactory;


/**
 * @see    https://docs.admincolumns.com/article/58-how-to-setup-local-storage
 */
class AddRepository
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

    /**
     * @param    array<string,ListScreenRepository>    $repositories
     * @param    ListScreenRepositoryFactory    $factory
     * @return    array<string,ListScreenRepository>
     */
    public function __invoke(
        array $repositories,
        ListScreenRepositoryFactory $factory
    )
    {
        $rules = new Rules();
        $rules->add_rule( new Rule\EqualType( 'brick' ) );

        $upload_dir = wp_upload_dir();

        $repositories[ 'brick' ] = $factory->create(
            $upload_dir[ 'basedir' ] . $this->dirname,
            /* $writable = */ true,
            $rules
        );

        return $repositories;
    }
}
