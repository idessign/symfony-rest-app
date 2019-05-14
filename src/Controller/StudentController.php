<?php

namespace App\Controller;

use App\Entity\School;
use App\Entity\Student;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends AbstractFOSRestController
{
	/**
	 * List of Students
	 * @Rest\Get("/student")
	 * @return Response
	 */
	public function list(Request $request)
	{
		$page = $request->query->get('page', 1);

		$repository = $this->getDoctrine()->getRepository(Student::class);
		$students = $repository->selectList($page);
		return $this->handleView($this->view($students));
	}

	/**
	 * Student data
	 * @Rest\Get("/student/{id}")
	 * @return Response
	 */
	public function show($id)
	{
		$repository = $this->getDoctrine()->getRepository(Student::class);
		$student = $repository->show($id);

		if (empty($student)) {
			return $this->handleView($this->view(['status' => 'Student not found'], Response::HTTP_NOT_FOUND));
		}

		return $this->handleView($this->view($student));
	}

	/**
	 * Add Student
	 * @Rest\Post("/student")
	 * @return Response
	 */
	public function new(Request $request)
	{
		$name = $request->get('name');
		$school = $request->get('school');

		if(empty($name) || empty($school)) {
			return $this->handleView($this->view(['status' => 'Empty data are not allowed'], Response::HTTP_NOT_ACCEPTABLE));
		}

		$em = $this->getDoctrine()->getManager();
		$school = $em->find(School::class, $school);

		$student = new Student();
		$student->setName($name);
		$student->setSchool($school);

		$em->merge($student);
		$em->flush();

		return $this->handleView($this->view(['status' => 'Student added'], Response::HTTP_CREATED));
	}

	/**
	* Update Student
	* @Rest\Put("/student/{id}")
	* @return Response
	*/
	public function update($id, Request $request)
	{
		$name = $request->get('name');
		$school = $request->get('school');
		$em = $this->getDoctrine()->getManager();
		$student = $this->getDoctrine()->getRepository(Student::class)->find($id);

		if (empty($student)) {
			return $this->handleView($this->view(['status' => 'Student not found'], Response::HTTP_NOT_FOUND));
		}
		elseif (!empty($name) && !empty($school)){
			$student->setName($name);
			$em->flush();
			return $this->handleView($this->view(['status' => 'Student data updated'], Response::HTTP_OK));
		}
		else {
			return $this->handleView($this->view(['status' => 'Empty data are not allowed'], Response::HTTP_NOT_ACCEPTABLE));
		}
	}

	/**
	 * Delete Student
	 * @Rest\Delete("/student/{id}")
	 * @return Response
	 */
	public function delete($id)
	{
		$em = $this->getDoctrine()->getManager();
		$student = $this->getDoctrine()->getRepository(Student::class)->find($id);

		if (empty($student)) {
			return $this->handleView($this->view(['status' => 'Student not found'], Response::HTTP_NOT_FOUND));
		}
		else {
			$em->remove($student);
			$em->flush();
		}

		return $this->handleView($this->view(['status' => 'Student deleted'], Response::HTTP_OK));
	}
}
