<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    

    public function __construct(
        private $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file, string $subFolder = '')
    {
        $this->targetDirectory .= '/' . $subFolder;

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            $fileName = null;
        }

        return $fileName;
    }

    public function remove(string $name, string $subForlder = '')
    {
        $file = $this->targetDirectory . '/' . $subForlder . '/' . $name;
        if (file_exists($file))
            unlink($file);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function setTargetDirectory(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        return $this;
    }
}