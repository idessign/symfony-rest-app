<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolRepository")
 */
class School
{
	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="school")
	 */
	private $students;

	public function __construct()
	{
		$this->students = new ArrayCollection();
	}

    /**
	 * @ORM\GeneratedValue()
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $name;

	public function getName(): text
	{
		return $this->name;
	}

	/**
	 * @ORM\Column(type="text")
	 */
	private $description;

	public function getDescription(): text
	{
		return $this->description;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return Collection|Student[]
	 */
	public function getStudents()
	{
		return $this->students;
	}
}
