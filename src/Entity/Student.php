<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="students")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $school;

	/**
	 * @ORM\Column(type="text")
	 */
	private $name;

	public function getName()
	{
		return $this->name;
	}

	public function getSchool()
	{
		return $this->school;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setSchool($school)
	{
		$this->school = $school;
	}
}
