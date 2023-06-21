<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PictureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['search'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['search'])]
    private ?string $file = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    /**
     * Le fichier upload : pas de lien ORM
     */
    private UploadedFile $pictureFile;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * get le fichier upload : pas de lien ORM
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Le fichier upload : pas de lien ORM
     */
    public function setPictureFile($pictureFile): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }
}
