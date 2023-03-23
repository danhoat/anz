<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\Util;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\Enum\Device;
use QMS3\Brick\Util\DeviceDetector;

require_once("vendor/autoload.php");


class DeviceDetectorTest extends TestCase
{
    public function test_PC_環境でデバイス検出()
    {
        $global_server = [
            'REMOTE_ADDR' => '::1',
            'REMOTE_PORT' => '49654',
            'SERVER_SOFTWARE' => 'PHP 7.4.5 Development Server',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '3333',
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/index.php',
            'SCRIPT_FILENAME' => '/path/to/index.php',
            'PHP_SELF' => '/index.php',
            'HTTP_HOST' => 'localhost:3333',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.9,en;q=0.8',
            'REQUEST_TIME_FLOAT' => 1613181757.920682,
            'REQUEST_TIME' => 1613181757,
        ];

        $detector = new DeviceDetector($global_server);
        $device = $detector->detect();

        $this->assertEquals($device->value(), Device::PC);
    }

    public function test_sp_ディレクトリ配下にアクセスしてデバイス検出()
    {
        $global_server = [
            'REMOTE_ADDR' => '::1',
            'REMOTE_PORT' => '49880',
            'SERVER_SOFTWARE' => 'PHP 7.4.5 Development Server',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '3333',
            'REQUEST_URI' => '/sp/foo/bar/',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/sp/foo/bar/index.php',
            'SCRIPT_FILENAME' => '/path/to/sp/foo/bar/index.php',
            'PHP_SELF' => '/sp/foo/bar/index.php',
            'HTTP_HOST' => 'localhost:3333',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.9,en;q=0.8',
            'REQUEST_TIME_FLOAT' => 1613183855.148649,
            'REQUEST_TIME' => 1613183855,
        ];

        $detector = new DeviceDetector($global_server);
        $device = $detector->detect();

        $this->assertEquals($device->value(), Device::SP);
    }

    public function test_SP_環境でデバイス検出()
    {
        $global_server = [
            'REMOTE_ADDR' => '::1',
            'REMOTE_PORT' => '49984',
            'SERVER_SOFTWARE' => 'PHP 7.4.5 Development Server',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '3333',
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/index.php',
            'SCRIPT_FILENAME' => '/path/to/index.php',
            'PHP_SELF' => '/index.php',
            'HTTP_HOST' => 'localhost:3333',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.9,en;q=0.8',
            'REQUEST_TIME_FLOAT' => 1613184003.64793,
            'REQUEST_TIME' => 1613184003,
        ];

        $detector = new DeviceDetector($global_server);
        $device = $detector->detect();

        $this->assertEquals($device->value(), Device::SP);
    }
}
