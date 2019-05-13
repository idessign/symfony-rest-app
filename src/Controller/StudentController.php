<?php

namespace App\Controller;

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
		$student = $repository->find($id);
		return $this->handleView($this->view($student));
	}

	/**
	 * Add Student
	 * @Rest\Post("/student")
	 * @return Response
	 */
	public function new(Request $request)
	{
		$data = new Student;
		$name = $request->get('name');
		$schoolId = $request->get('school_id');

		if(empty($name) || empty($schoolId)) {
			return $this->handleView($this->view(['status' => 'Empty data are not allowed'], Response::HTTP_NOT_ACCEPTABLE));
		}

		$data->setName($name);
		$data->setSchoolId($schoolId);
		$em = $this->getDoctrine()->getManager();
		$em->persist($data);
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
		$schoolId = $request->get('school_id');
		$em = $this->getDoctrine()->getManager();
		$student = $this->getDoctrine()->getRepository(Student::class)->find($id);

		if (empty($student)) {
			return $this->handleView($this->view(['status' => 'Student not found'], Response::HTTP_NOT_FOUND));
		}
		elseif(!empty($name) && !empty($schoolId)){
			$student->setName($name);
			$student->setSchoolId($schoolId);
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
