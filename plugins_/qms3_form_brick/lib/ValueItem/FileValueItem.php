<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueItem;

use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Enum\UploadStatus;


/**
 * @property-read    string          filename
 * @property-read    int             filesize
 * @property-read    string          mime_type
 * @property-read    UploadStatus    status
 * @property-read    string          url
 */
class FileValueItem
{
    use ReadonlyProps;

    /** @var    string */
    private $filename;

    /** @var    int */
    private $filesize;

    /** @var    string */
    private $mime_type
    ;

    /** @var    int */
    private $error_no;

    /** @var    UploadStatus */
    private $status;

    /** @var    string */
    private $url;

    /** @var    string */
    private $filepath;

    /**
     * @param    string          $filename
     * @param    int             $filesize
     * @param    string          $mime_type
     * @param    int             $error_no
     * @param    UploadStatus    $status
     * @param    string          $url
     * @param    string          $filepath
     */
    public function __construct(
        $filename,
        $filesize,
        $mime_type,
        $error_no,
        $status,
        $url,
        $filepath = ''
    )
    {
        $this->filename  = $filename;
        $this->filesize  = $filesize;
        $this->mime_type = $mime_type;
        $this->error_no  = $error_no;
        $this->status    = $status;
        $this->url       = $url;
        $this->filepath       = $filepath;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return "{$this->filename} ( {$this->url} )";
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _filename()
    {
        return $this->filename;
    }

    /**
     * @return    int
     */
    protected function _filesize()
    {
        return $this->filesize;
    }

    /**
     * @return    string
     */
    protected function _mime_type()
    {
        return $this->mime_type;
    }

    /**
     * @return    int
     */
    protected function _error_no()
    {
        return $this->error_no;
    }

    /**
     * @return    UploadStatus
     */
    protected function _status()
    {
        return $this->status;
    }

    /**
     * @return    string
     */
    protected function _url()
    {
        return $this->url;
    }

    /**
     * @return    string
     */
    protected function _filepath()
    {
        return $this->filepath;
    }
}
