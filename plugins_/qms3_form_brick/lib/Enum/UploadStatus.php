<?php
declare(strict_types=1);

namespace QMS3\Brick\Enum;

use QMS3\Brick\Enum\Enum;


/**
 * @see    https://www.php.net/manual/ja/features.file-upload.errors.php
 */
class UploadStatus extends Enum
{
    /**
     * アップロード成功
     */
    const OK = "OK";

    /**
     * 何もアップロードされていない
     */
    const NOT_UPLOADED = "NOT_UPLOADED";

    /**
     * ファイルサイズが大きすぎてアップロードできない
     */
    const OVERSIZE = "OVERSIZE";

    /**
     * アップロード処理中のエラー
     */
    const RUNTIME_ERROR = "RUNTIME_ERROR";

    /**
     * 不明なエラー
     */
    const UNKNOWN_ERROR = "UNKNOWN_ERROR";

    /**
     * サポートされていないファイル
     */
    const UNSUPPORTED_FILE = "UNSUPPORTED_FILE";
}
