<?php

namespace App\Controller;

use App\Entity\School;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends AbstractFOSRestController
{
    /**
	 * List of Schools
     * @Rest\Get("/school")
	 * @return Response
     */
    public function list(Request $request)
    {
		$page = $request->query->get('page', 1);

		$repository = $this->getDoctrine()->getRepository(School::class);
		$schools = $repository->selectList($page);

		if ($schools === null) {
			return $this->handleView($this->view(['status' => 'Result empty'], Response::HTTP_NOT_FOUND));
		}

		return $this->handleView($this->view($schools));
    }

	/**
	 * School data
	 * @Rest\Get("/school/{id}")
	 * @return Response
	 */
	public function show($id)
	{
		$repository = $this->getDoctrine()->getRepository(School::class);
		$school = $repository->find($id);

		if ($school === null) {
			return $this->handleView($this->view(['status' => 'Result empty'], Response::HTTP_NOT_FOUND));
		}

		return $this->handleView($this->view($school));
	}

	/**
	 * Add School
	 * @Rest\Post("/school")
	 * @return Response
	 */
	public function new(Request $request)
	{
		$data = new School;
		$name = $request->get('name');
		$description = $request->get('description');

		if(empty($name) || empty($description)) {
			return $this->handleView($this->view(['status' => 'Empty data are not allowed'], Response::HTTP_NOT_FOUND));
		}

		$data->setName($name);
		$data->setDescription($description);
		$em = $this->getDoctrine()->getManager();
		$em->persist($data);
		$em->flush();

		return $this->handleView($this->view(['status' => 'School added'], Response::HTTP_CREATED));
	}

	/**
	 * Update School
	 * @Rest\Put("/school/{id}")
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$name = $request->get('name');
		$description = $request->get('description');
		$em = $this->getDoctrine()->getManager();
		$school = $this->getDoctrine()->getRepository(School::class)->find($id);

		if (empty($school)) {
			return $this->handleView($this->view(['status' => 'School not found'], Response::HTTP_NOT_FOUND));
		}
		elseif(!empty($name) && !empty($description)){
			$school->setName($name);
			$school->setDescription($description);
			$em->flush();
			return $this->handleView($this->view(['status' => 'School data updated'], Response::HTTP_OK));
		}
		else {
			return $this->handleView($this->view(['status' => 'Empty data are not allowed'], Response::HTTP_NOT_ACCEPTABLE));
		}
	}

	/**
	 * Delete School
	 * @Rest\Delete("/school/{id}")
	 * @return Response
	 */
	public function delete($id)
	{
		$em = $this->getDoctrine()->getManager();
		$school = $this->getDoctrine()->getRepository(School::class)->find($id);

		if (empty($school)) {
			return $this->handleView($this->view(['status' => 'School not found'], Response::HTTP_NOT_FOUND));
		}
		else {
			$em->remove($school);
			$em->flush();
		}

		return $this->handleView($this->view(['status' => 'School deleted'], Response::HTTP_OK));
	}
}
