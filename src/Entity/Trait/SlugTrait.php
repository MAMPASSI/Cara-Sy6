<?php 
namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;

trait SlugTrait
{
    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    public function getSlug(): ?string
    {
        return $this->Slug;
    }

    public function setSlug(string $Slug): self
    {
        $this->Slug = $Slug;

        return $this;
    }
}