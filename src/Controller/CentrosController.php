<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Repository\CentrosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentrosController extends AbstractController
{
    #[Route('/centros', name: 'app_centros')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $centrosRepository = $entityManager->getRepository(Centros::class);
        $centros = $centrosRepository->findAll();

        $data = [];
        foreach ($centros as $centro) {
            $data[] = [
                'id' => $centro->getId(),
                'nombre' => $centro->getNombreCentro(),
                'direccion' => $centro->getDireccionCentro(),
                'telefono' => $centro->getTelefonoCentro(),
                'email' => $centro->getEmailCentro(),
                'password' => $centro->getPassword(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
