<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    public const STATUS_AUTOMATIC = 'Automatique';
    public const STATUS_MANUAL = 'Manuelle';
    public const STATUS_LEVEL_0 = 'Niveau 0';
    public const STATUS_LEVEL_1 = 'Niveau 1';
    public const STATUS_LEVEL_2 = 'Niveau 2';
    public const STATUS_LEVEL_3 = 'Niveau 3';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du véhicule est obligatoire !")]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom du véhicule doit avoir au moins {{ limit }} caractères')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le prix du véhicule est obligatoire !')]
    private ?int $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le kilométrage du véhicule est obligatoire !')]
    private ?int $kilometers = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description courte est obligatoire")]
    #[Assert\Length(min: 20, minMessage: "La description courte doit faire au moins {{ limit }} caractères")]
    private ?string $shortDescription = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $circulationAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Model $model = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Images::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Type $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La boite de vitesse est obligatoire !')]
    private ?string $gearbox = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La puissance fiscale est obligatoire !')]
    private ?string $fiscalhorsepower = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Energy $energy = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Critair $critair = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): static
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getCirculationAt(): ?\DateTimeImmutable
    {
        return $this->circulationAt;
    }

    public function setCirculationAt(\DateTimeImmutable $circulationAt): static
    {
        $this->circulationAt = $circulationAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getGearbox(): ?string
    {
        return $this->gearbox;
    }

    public function setGearbox(string $gearbox): static
    {
        $this->gearbox = $gearbox;

        return $this;
    }

    public function getFiscalhorsepower(): ?string
    {
        return $this->fiscalhorsepower;
    }

    public function setFiscalhorsepower(string $fiscalhorsepower): static
    {
        $this->fiscalhorsepower = $fiscalhorsepower;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getEnergy(): ?Energy
    {
        return $this->energy;
    }

    public function setEnergy(?Energy $energy): static
    {
        $this->energy = $energy;

        return $this;
    }

    public function getCritair(): ?Critair
    {
        return $this->critair;
    }

    public function setCritair(?Critair $critair): static
    {
        $this->critair = $critair;

        return $this;
    }
}
