<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @UniqueEntity("nom", message="Ce produit existe déjà.")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length( min= 3, minMessage= "Le nom doit faire {{ limit }} caractères au moins", max= 100, maxMessage="Le nom ne peut dépasser {{ limit }} caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length( min= 3, minMessage= "La description doit faire {{ limit }} caractères au moins", max= 1200, maxMessage="La description ne peut dépasser {{ limit }} caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Le prix ne peut pas être inférieur à 1.")
     * @Assert\Type(
     *     type="integer",
     *     message="Le prix doit être un entier."
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}
