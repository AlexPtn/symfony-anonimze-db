<?php

namespace App\Entity;

use App\Annotation\Anonymize;
use App\Repository\SensivityClassRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Type;

#[ORM\Entity(repositoryClass: SensivityClassRepository::class)]
class SensivityClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NotSensivity = null;

    #[Anonymize(type:"phone")]
    #[ORM\Column(length: 255)]
    private ?string $Sensivity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotSensivity(): ?string
    {
        return $this->NotSensivity;
    }

    public function setNotSensivity(string $NotSensivity): self
    {
        $this->NotSensivity = $NotSensivity;

        return $this;
    }

    public function getSensivity(): ?string
    {
        return $this->Sensivity;
    }

    public function setSensivity(string $Sensivity): self
    {
        $this->Sensivity = $Sensivity;

        return $this;
    }
}
