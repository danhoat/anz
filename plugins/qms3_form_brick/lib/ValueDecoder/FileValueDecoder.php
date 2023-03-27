<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use DateTime;
use DateTimeZone;
use QMS3\Brick\Enum\UploadStatus;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\FileValueContainer;
use QMS3\Brick\ValueItem\FileValueItem;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;
use RuntimeException;


class FileValueDecoder implements ValueDecoderInterface
{
    /** @var    StructureRow */
    private $structure_row;

    /**
     * @param    StructureRow    $structure_row
     */
    public function __construct(StructureRow $structure_row)
    {
        $this->structure_row = $structure_row;
    }

    /**
     * @param     ServerRequest          $request
     * @param     array<string,mixed>    $param_values
     * @return    TextValueContainer
     */
    public function decode(ServerRequest $request, array $param_values)
    {
        $name = $this->structure_row->name;

        if (
            $request->has($name)
            && ($value = $request->get($name))
            && is_array($value)
        ) {
            if ($value["error"] == UPLOAD_ERR_OK && isset($value["url"])) {
                $status = new UploadStatus(UploadStatus::OK);
                $item = new FileValueItem(
                    /* $filename  = */ $value["name"],
                    /* $filesize  = */ $value["size"],
                    /* $mime_type = */ $value["type"],
                    /* $error_no  = */ $value["error"],
                    /* $status    = */ $status,
                    /* $url       = */ $value["url"]
                );
                return new FileValueContainer($item);
            }

            if ($value["error"] == UPLOAD_ERR_OK) {
                if (!$this->check_file_type($value["tmp_name"])) {
                    $status = new UploadStatus(UploadStatus::UNSUPPORTED_FILE);
                    $item = new FileValueItem("", 0, "", UPLOAD_ERR_NO_FILE, $status, "");
                    return new FileValueContainer($item);
                }

                list($dir, $filename) = $this->move_uploaded_file($value["tmp_name"], $value["name"]);
                $upload_path = $dir . DIRECTORY_SEPARATOR . $filename;

                $status = new UploadStatus(UploadStatus::OK);
                $item = new FileValueItem(
                    /* $filename  = */ $filename,
                    /* $filesize  = */ $value["size"],
                    /* $mime_type = */ $value["type"],
                    /* $error_no  = */ $value["error"],
                    /* $status    = */ $status,
                    /* $url       = */ $this->upload_url($upload_path)
                );
                return new FileValueContainer($item);
            }

            if ($value["error"] == UPLOAD_ERR_NO_FILE) {
                $status = new UploadStatus(UploadStatus::EMPTY);
                $item = new FileValueItem("", 0, "", $value["error"], $status, "");
                return new FileValueContainer($item);
            }

            if (
                $value["error"] == UPLOAD_ERR_INI_SIZE
                || $value["error"] == UPLOAD_ERR_FORM_SIZE
            ) {
                $status = new UploadStatus(UploadStatus::OVERSIZE);
                $item = new FileValueItem("", 0, "", $value["error"], $status, "");
                return new FileValueContainer($item);
            }

            if (
                $value["error"] == UPLOAD_ERR_PARTIAL
                || $value["error"] == UPLOAD_ERR_NO_TMP_DIR
                || $value["error"] == UPLOAD_ERR_CANT_WRITE
                || $value["error"] == UPLOAD_ERR_EXTENSION
            ) {
                $status = new UploadStatus(UploadStatus::RUNTIME_ERROR);
                $item = new FileValueItem("", 0, "", $value["error"], $status, "");
                return new FileValueContainer($item);
            }

            $status = new UploadStatus(UploadStatus::UNKNOWN_ERROR);
            $item = new FileValueItem("", 0, "", $value["error"], $status, "");
            return new FileValueContainer($item);
        }

        $status = new UploadStatus(UploadStatus::EMPTY);
        $item = new FileValueItem("", 0, "", UPLOAD_ERR_NO_FILE, $status, "");
        return new FileValueContainer($item);
    }

    // ====================================================================== //

    /**
     * @param     string      $temp_path
     * @return    bool
     */
    private function check_file_type($temp_path)
    {
        $magic = file_get_contents($temp_path, false, null, 0, 12);

        switch (true) {
            case strpos($magic, "GIF") === 0: return true;  // gif
            case strpos($magic, "\x89PNG") === 0: return true;  // png
            case strpos($magic, "\xFF\xD8") === 0: return true;  // jpeg
            case strpos($magic, "RIFF") === 0 && strpos($magic, "WEBP") === 8: return true;  // webp
            case strpos($magic, "%PDF-1") === 0: return true;  // pdf
            default: return false;
        }
    }

    /**
     * @param     string      $temp_path
     * @param     string      $filename
     * @return    string[]
     */
    private function move_uploaded_file($temp_path, $filename)
    {
        $dir = $this->upload_dir();
        $filename = $this->sanitize_filename($dir, $filename);

        if (is_uploaded_file($temp_path)) {
            if (move_uploaded_file($temp_path, $dir . DIRECTORY_SEPARATOR . $filename)) {
                return [$dir, $filename];
            } else {
                throw new RuntimeException("ファイルアップロードに失敗しました: \$temp_path: {$temp_path}");
            }
        } else {
            throw new RuntimeException("不正なファイルです: \$temp_path: {$temp_path}");
        }
    }

    /**
     * @return    string
     */
    private function upload_dir()
    {
        $tz = new DateTimeZone("Asia/Tokyo");
        $now = new DateTime("now", $tz);

        $sub_dir = $now->format("Y/m");
        $dir = rtrim(QMS3_FORM_UPLOAD_DIR, "/") . "/" . $sub_dir;

        if (!is_dir($dir) && !mkdir($dir, 0777, /* $recursive = */ true)) {
            throw new RuntimeException("ファイルアップロード先のディレクトリが存在しません: \$dir: {$dir}");
        }

        if (!is_writable($dir) && !chmod($dir, 0777)) {
            throw new RuntimeException("ファイルアップロード先のディレクトリに書き込みが許可されていません: \$dir: {$dir}");
        }

        return realpath($dir);
    }

    /**
     * @param     string    $upload_dir
     * @param     string    $filename
     * @return    string
     */
    private function sanitize_filename($upload_dir, $filename)
    {
        $filename = str_replace(
            ["/", "\\", ":", "*", "?", '"', "<", ">", "|"],
            "",
            $filename
        );
        $filename = preg_replace("/[\r\n\t -]+/", "-", $filename);

        setlocale(LC_ALL, "ja_JP.UTF-8");
        $pathinfo = pathinfo($filename);
        $filename = $pathinfo["filename"];
        $ext = isset($pathinfo["extension"]) ? ".{$pathinfo['extension']}" : "";

        if ( defined( 'QMS3_FORM_UPLOAD_RANDOM_FILENAME' ) && QMS3_FORM_UPLOAD_RANDOM_FILENAME ) {
            $filename = uniqid( '', true );
        }

        if (file_exists($upload_dir . "/" . $filename . $ext)) {
            $appendix = 2;
            while (file_exists($upload_dir . "/" . $filename . "-" . $appendix . $ext)) {
                $appendix++;
            }

            $filename = $filename . "-" . $appendix;
        }

        return $filename . $ext;
    }

    // ====================================================================== //

    /**
     * @param     string    $upload_path    アップロードされたファイルのパス
     * @return    string                    アップロードされたファイルの URL
     */
    private function upload_url($upload_path)
    {
        $upload_paths = explode(DIRECTORY_SEPARATOR, realpath($upload_path));
        $script_paths = explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]);

        for (
            $len = 1;
            array_slice($upload_paths, 0, $len) == array_slice($script_paths, 0, $len);
            $len++
        ) { /* 何もしない */ }

        $upload_paths = array_slice($upload_paths, $len - 1);  // $upload_paths と $script_paths の各階層のうち、共通しているものを捨てる
        $script_paths = array_slice($script_paths, $len - 1);  // $upload_paths と $script_paths の各階層のうち、共通しているものを捨てる


        $script_url = $_SERVER["REQUEST_URI"];
        $script_url = preg_replace("/\?.*$/", "", $script_url);  // query string 削除
        if (!preg_match("%(\.html|\.php|/)$%i", $script_url)) {
            $script_url .= "/";  // trailing slash 追加
        }

        $script_url_dirs = explode("/", $script_url);
        array_pop($script_url_dirs);  // URL の末尾はファイル名なので捨てる
        array_splice($script_url_dirs, -1, count($script_paths) - 1);  // この操作は $script_url_dirs を `count($script_paths) - 1` 階層 上ることに相当する


        $host = $_SERVER["HTTP_HOST"];
        $protocol = empty($_SERVER["HTTPS"]) ? "http" : "https";
        $dirs = array_merge($script_url_dirs, $upload_paths);

        return $protocol . "://" . $host . join("/", $dirs);
    }
}
