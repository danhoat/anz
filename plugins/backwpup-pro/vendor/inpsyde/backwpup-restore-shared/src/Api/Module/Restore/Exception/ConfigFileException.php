<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Module\Restore\Exception;

use Inpsyde\Restore\Api\Exception\RestoreExceptionInterface;
use RuntimeException;

/**
 * Class ConfigFileException
 *
 * @author  Brandon Olivares <b.olivares@inpsyde.com>
 * @package Inpsyde\Restore\Api\Module\Restore\Exception
 */
final class ConfigFileException extends RuntimeException implements RestoreExceptionInterface
{

}
