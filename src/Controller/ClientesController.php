<?php

namespace App\Controller;

use App\Entity\Clientes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientesController extends AbstractController
{
    #[Route('/clientes', name: 'app_clientes')]
    public function listProductos(EntityManagerInterface $entityManager): JsonResponse
    {

        $clientesRepository = $entityManager->getRepository(Clientes::class);
        $clientes = $clientesRepository->findAll();

        $data = [];
        foreach ($clientes as $cliente) {
            $data[] = [
                'id' => $cliente->getId(),
                'centro' => $cliente->getEmpleadosCentro()->getNombreCentro(),
                'nombre' => $cliente->getNombreEmpleado(),
                'apellidos' => $cliente->getApellidosEmpleado(),
                'rol' => $cliente->getRolEmpleado(),
                'horario_inicio' => $cliente->getHorarioInicio(),
                'horario_fin' => $cliente->getHorarioFin(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}