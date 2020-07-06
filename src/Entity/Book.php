<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Orm\Mapping as ORM;

class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=5242880)
     */
    private string $text;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}