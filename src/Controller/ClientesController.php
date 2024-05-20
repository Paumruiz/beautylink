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
    #[Route('/clientes/{id}', name: 'app_clientes')]
    public function listProductos(int $id, EntityManagerInterface $entityManager): JsonResponse
    {

        $clientes = $entityManager->getRepository(Clientes::class)->findBy(['id_centro' => $id]);;

        $data = [];
        foreach ($clientes as $cliente) {
            $data[] = [
                'id' => $cliente->getId(),
                'centro' => $cliente->getIdCentroCliente()->getNombreCentro(),
                'nombre' => $cliente->getNombreCliente(),
                'apellidos' => $cliente->getApellidosCliente(),
                'email' => $cliente->getEmailCliente(),
                'telefono' => $cliente->getTelefonoCliente(),
                'password' => $cliente->getPassword(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
