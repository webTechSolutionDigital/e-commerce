<?php


namespace App\Entity\Traits;

use App\Entity\Recipe;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\SluggerInterface;

trait SlugTrait
{
   
    public function __construct(public SluggerInterface $slugger, Recipe $recipe) {
        $this->slugger = $this->$recipe->getTitle()->lower();
        // $category->setSlug($this->slugger->slug($category->getName())->lower());

    }

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }


}

