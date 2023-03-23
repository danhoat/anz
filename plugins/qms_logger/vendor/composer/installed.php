<?php return array(
    'root' => array(
        'name' => 'atsu-maru/qms_logger',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'fbfa3a17047a4014a9604cf289a423f2745a3da2',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'atsu-maru/qms_logger' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'fbfa3a17047a4014a9604cf289a423f2745a3da2',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => '2.8.0',
            'version' => '2.8.0.0',
            'reference' => '720488632c590286b88b80e62aa3d3d551ad4a50',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => '3.0.0',
            'version' => '3.0.0.0',
            'reference' => 'fe5ea303b0887d5caefd3d431c3e61ad47037001',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '1.0.0 || 2.0.0 || 3.0.0',
            ),
        ),
    ),
);
