<?php

namespace App\Service;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FileUploader
{
    /**
     * @var FilesystemOperator
     */
    private $fileSystem;

    public function __construct(
        FilesystemOperator $defaultStorage)
    {
        $this->fileSystem = $defaultStorage;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadBase64File(
        string $base64File
    ): string
    {
        $extension = explode('/', mime_content_type($base64File))[1];
        $data = explode(',', $base64File);
        $fileName = sprintf('%s.%s', uniqid('book_', true), $extension);

        $this->fileSystem->write($fileName, base64_decode($data[1]));

        return $fileName;
    }
}