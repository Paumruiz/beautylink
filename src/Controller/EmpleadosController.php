<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Empleados;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class EmpleadosController extends AbstractController
{
    #[Route('/empleados', name: 'app_empleados', methods: ['GET'])]
    public function listProductos(EntityManagerInterface $entityManager): JsonResponse
    {

        $empleadosRepository = $entityManager->getRepository(Empleados::class);
        $empleados = $empleadosRepository->findAll();

        $data = [];
        foreach ($empleados as $empleado) {
            $data[] = [
                'id' => $empleado->getId(),
                'centro' => $empleado->getEmpleadosCentro()->getNombreCentro(),
                'nombre' => $empleado->getNombreEmpleado(),
                'apellidos' => $empleado->getApellidosEmpleado(),
                'rol' => $empleado->getRolEmpleado(),
                'horario_inicio' => $empleado->getHorarioInicio(),
                'horario_fin' => $empleado->getHorarioFin(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/empleados', name: 'app_empleados_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $centroId = $data['centroId'];

        $centro = $entityManager->getRepository(Centros::class)->find($centroId);

        $empleadoEntity = new Empleados();
        $empleadoEntity->setEmpleadosCentro($centro);
        $empleadoEntity->setNombreEmpleado($data['nombre']);
        $empleadoEntity->setApellidosEmpleado($data['apellidos']);
        $empleadoEntity->setRolEmpleado($data['rol']);
        $empleadoEntity->setHorarioInicio($data['horario_inicio']);
        $empleadoEntity->setHorarioFin($data['horario_fin']);

        $entityManager->persist($empleadoEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Empleado insertado correctamente'], Response::HTTP_CREATED);
    }
}
