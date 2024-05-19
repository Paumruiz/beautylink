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

    #[Route('/empleados/{centroId}', name: 'app_empleados_centro', methods: ['GET'])]
    public function listEmpleadosPorCentro(int $centroId, EntityManagerInterface $entityManager): JsonResponse
    {
        $empleadosRepository = $entityManager->getRepository(Empleados::class);

        // Obtener los empleados por el ID del centro
        $empleados = $empleadosRepository->findBy(['id_centro' => $centroId]);

        $data = [];
        foreach ($empleados as $empleado) {
            $data[] = [
                'id' => $empleado->getId(),
                'centro' => $empleado->getEmpleadosCentro()->getNombreCentro(), // Asumiendo que 'getCentro()' es el método para obtener el centro asociado al empleado
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
        $empleadoEntity->setHorarioInicio(new \DateTime($data['horario_inicio']));
        $empleadoEntity->setHorarioFin(new \DateTime($data['horario_fin']));

        $entityManager->persist($empleadoEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Empleado insertado correctamente'], Response::HTTP_CREATED);
    }

    #[Route('/empleados/{id}', name: 'app_empleados_update', methods: ['PATCH'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $empleadoEntity = $entityManager->getRepository(Empleados::class)->find($id);

        if (!$empleadoEntity) {
            return new JsonResponse(['error' => 'Empleado no encontrado'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['centroId'])) {
            $centroId = $data['centroId'];
            $centro = $entityManager->getRepository(Centros::class)->find($centroId);
            if (!$centro) {
                return new JsonResponse(['error' => 'Centro no encontrado'], Response::HTTP_NOT_FOUND);
            }
            $empleadoEntity->setEmpleadosCentro($centro);
        }

        if (isset($data['nombre'])) {
            $empleadoEntity->setNombreEmpleado($data['nombre']);
        }

        if (isset($data['apellidos'])) {
            $empleadoEntity->setApellidosEmpleado($data['apellidos']);
        }

        if (isset($data['rol'])) {
            $empleadoEntity->setRolEmpleado($data['rol']);
        }

        if (isset($data['horario_inicio'])) {
            $empleadoEntity->setHorarioInicio(new \DateTime($data['horario_inicio']));
        }

        if (isset($data['horario_fin'])) {
            $empleadoEntity->setHorarioFin(new \DateTime($data['horario_fin']));
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Empleado actualizado correctamente'], Response::HTTP_OK);
    }

    #[Route('/empleados/{id}', name: 'app_empleados_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $empleado = $entityManager->getRepository(Empleados::class)->find($id);

        if (!$empleado) {
            return new JsonResponse(['error' => 'Empleado no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($empleado);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Empleado eliminado correctamente'], Response::HTTP_OK);
    }
}

/* {
    "centroId": 1,
    "nombre": "Emp 2",
    "apellidos": "Ap4 Ap5",
    "rol": "Peluquería",
    "horario_inicio": "2024-01-01 09:00:00",
    "horario_fin": "2024-01-01 17:00:00"
}
 */