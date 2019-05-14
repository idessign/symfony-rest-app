<?php

namespace App\Controller;

use App\Entity\School;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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
    	// Get page
		$page = $request->query->get('page', 1);

		// Create cache
		$createCache = $request->query->get('cache');

		// Get School
		$repository = $this->getDoctrine()->getRepository(School::class);
		$schools = $repository->selectList($page);

		// Check result
		if ($schools === null) {
			return $this->handleView($this->view(['status' => 'Result empty'], Response::HTTP_NOT_FOUND));
		}

		// Count students cache
		$cache = new FilesystemAdapter();

		// Array item counter
		$counter = 0;

		foreach ($schools as $school) {
			$schoolId = $school['id'];

			// create a new item by trying to get it from the cache
			$countStudent = $cache->getItem('countStudent.' . $schoolId);

			// Check cache data exists
			if (!$countStudent->isHit() || $createCache == 'create') {
				$schoolStudents = $this->getDoctrine()
					->getRepository(School::class)
					->find($schoolId);
				$students = $schoolStudents->getStudents();

				// assign a value to the item and save it
				// $countStudent->set(count($students));
				$countStudent->set(count($students));
				$cache->save($countStudent);
			}

			// retrieve the value stored by the item
			$countStudent = $countStudent->get();

			// Add countStudent value in array
			$schools[$counter]['studentCount'] = $countStudent;

			$counter++;
		}

		return $this->handleView($this->view($schools));
    }

	/**
	 * School data
	 * @Rest\Get("/school/{id}")
	 * @return Response
	 */
	public function show($id, Request $request)
	{
		// Create cache
		$createCache = $request->query->get('cache');

		$repository = $this->getDoctrine()->getRepository(School::class);
		$school = $repository->show($id);

		if ($school === null) {
			return $this->handleView($this->view(['status' => 'Result empty'], Response::HTTP_NOT_FOUND));
		}

		// Count students cache
		$cache = new FilesystemAdapter();

		// create a new item by trying to get it from the cache
		$countStudent = $cache->getItem('countStudent.' . $id);

		// Check cache data exists
		if (!$countStudent->isHit() || $createCache == 'create') {
			$schoolStudents = $this->getDoctrine()
				->getRepository(School::class)
				->find($id);
			$students = $schoolStudents->getStudents();

			// assign a value to the item and save it
			// $countStudent->set(count($students));
			$countStudent->set(count($students));
			$cache->save($countStudent);
		}

		// retrieve the value stored by the item
		$countStudent = $countStudent->get();

		// Add countStudent value in array
		$school[0]['studentCount'] = $countStudent;

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
