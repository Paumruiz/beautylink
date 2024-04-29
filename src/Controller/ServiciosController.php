<?php

namespace App\Controller;

use App\Entity\Servicios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class ServiciosController extends AbstractController
{
    #[Route('/servicios', name: 'app_servicios')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $serviciosRepository = $entityManager->getRepository(Servicios::class);

        $servicios = $serviciosRepository->findAll();

        $formattedServicios = [];
        foreach ($servicios as $servicio) {
            $formattedServicios[] = [
                'id' => $servicio->getId(),
                'nombre' => $servicio->getNombreServicio(),
                'descripcion' => $servicio->getDescripcionServicio(),
                'duracion' => $servicio->getDuracionServicio(),
                'precio' => $servicio->getPrecioServicio(),
            ];
        }

        return new JsonResponse($formattedServicios, Response::HTTP_OK);
    }
}
