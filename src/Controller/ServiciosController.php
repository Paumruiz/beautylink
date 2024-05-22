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
    #[Route('/servicios', name: 'app_servicios', methods: ['GET'])]
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

    #[Route('/servicios', name: 'app_servicios_add', methods: ['POST'])]
    public function addServicio(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $servicioEntity = new Servicios();

        $servicioEntity->setNombreServicio($data['nombre']);
        $servicioEntity->setDescripcionServicio($data['descripcion']);
        $servicioEntity->setDuracionServicio($data['duracion']);
        $servicioEntity->setPrecioServicio($data['precio']);

        $entityManager->persist($servicioEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Servicio insertado correctamente'], Response::HTTP_CREATED);
    }

    #[Route('/servicios/{id}', name: 'app_servicios_update', methods: ['PATCH'])]
    public function updateServicio(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $servicioEntity = $entityManager->getRepository(Servicios::class)->find($id);

        if (!$servicioEntity) {
            return new JsonResponse(['error' => 'El servicio no existe'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['nombre'])) {
            $servicioEntity->setNombreServicio($data['nombre']);
        }
        if (isset($data['descripcion'])) {
            $servicioEntity->setDescripcionServicio($data['descripcion']);
        }
        if (isset($data['duracion'])) {
            $servicioEntity->setDuracionServicio($data['duracion']);
        }
        if (isset($data['precio'])) {
            $servicioEntity->setPrecioServicio($data['precio']);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Servicio actualizado correctamente'], Response::HTTP_OK);
    }

    #[Route('/servicios/{id}', name: 'app_servicios_delete', methods: ['DELETE'])]
    public function deleteServicio(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $servicioEntity = $entityManager->getRepository(Servicios::class)->find($id);

        if (!$servicioEntity) {
            return new JsonResponse(['error' => 'El servicio no existe'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($servicioEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Servicio eliminado correctamente'], Response::HTTP_OK);
    }
}
