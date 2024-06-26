<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 * @UniqueEntity("name", message = "Ce nom existe déjà")  
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"organizer_show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"type_list", "type_show", "type_list", "type_show", "organizer_list", "organizer_show", "organizer_create", "organizer_update", "random_all"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Organizer::class, mappedBy="type")
     */
    private $organizers;

    public function __construct()
    {
        $this->organizers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Organizer>
     */
    public function getOrganizers(): Collection
    {
        return $this->organizers;
    }

    public function addOrganizer(Organizer $organizer): self
    {
        if (!$this->organizers->contains($organizer)) {
            $this->organizers[] = $organizer;
            $organizer->setType($this);
        }

        return $this;
    }

    public function removeOrganizer(Organizer $organizer): self
    {
        if ($this->organizers->removeElement($organizer)) {
            // set the owning side to null (unless already changed)
            if ($organizer->getType() === $this) {
                $organizer->setType(null);
            }
        }

        return $this;
    }
}
