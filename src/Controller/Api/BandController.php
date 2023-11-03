<?php

namespace App\Controller\Api;

use App\Entity\Band;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/band", name="api_band_")
 */
class BandController extends AbstractController
{
    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(BandRepository $bandRepository): JsonResponse
    {
        return $this->json([
            'bands' => $bandRepository->findAll()], 200, [], ['groups' => 'band_list'
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     */
    public function show(Band $band): JsonResponse
    {
        return $this->json([
            'band' => $band], 200, [], ['groups' => 'band_show']);
    }

        /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $json = $request->getContent();
        $band = $serializer->deserialize($json, Band::class, 'json');
        $errorList = $validator->validate($band);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($band);
        $entityManager->flush();
        return $this->json($band, Response::HTTP_CREATED, [], ["groups" => 'band_create']);
    }
    /**
     * @Route("/{id<\d+>}", name="update", methods={"PATCH"})
     */
    public function update($id, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $band = $em->find(Band::class, $id);
        if ($band === null) {
            $errorMessage = [
                'message' => "Band not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }
        $json = $request->getContent();
        $serializer->deserialize($json, Band::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $band]);
        $errorList = $validator->validate($band);
        if (count($errorList) > 0) {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }
        $em->flush();
        return $this->json($band, Response::HTTP_OK, [], ['groups' => 'band_update']);
    }
    /**
     * @Route("/{id<\d+>}"), name="delete", methods={"DELETE"})
     */
    public function delete($id, EntityManagerInterface $em, BandRepository $bandRepository)
    {
        $band = $em->find(Band::class, $id);
        if ($band === null) {
            $errorMessage = [
                'message' => "Band not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }
        $bandRepository->remove($band, true);
        return $this->json($band, Response::HTTP_OK);
    }
}