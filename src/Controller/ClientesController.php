<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Clientes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/clientes', name: 'app_crear_cliente', methods: ['POST'])]
    public function crearCliente(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $centroId = $data['id_centro'];
        $centro = $entityManager->getRepository(Centros::class)->find($centroId);

        $cliente = new Clientes();
        $cliente->setNombreCliente($data['nombre']);
        $cliente->setApellidosCliente($data['apellidos']);
        $cliente->setEmailCliente($data['email']);
        $cliente->setTelefonoCliente($data['telefono']);
        $cliente->setPassword($data['password']);
        // Asume que id_centro es enviado en la petición. Asegúrate de validar y establecer relaciones correctamente.
        $cliente->setIdCentroCliente($centro);

        $entityManager->persist($cliente);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Cliente creado'], Response::HTTP_CREATED);
    }
}
