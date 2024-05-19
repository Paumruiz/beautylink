<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Citas;
use App\Entity\Clientes;
use App\Entity\Empleados;
use App\Entity\Servicios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class CitasController extends AbstractController
{
    #[Route('/citas/{id}', name: 'app_citas', methods: ['GET'])]
    public function showByCenterId(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $citas = $entityManager->getRepository(Citas::class)
            ->findBy(['id_centro' => $id], ['fecha_cita' => 'DESC']);


        if (empty($citas)) {
            return new JsonResponse(['error' => 'No hay citas para este centro'], Response::HTTP_NOT_FOUND);
        }

        $formattedCitas = [];
        foreach ($citas as $cita) {
            $formattedCitas[] = [
                'id' => $cita->getId(),
                'servicio' => $cita->getServicioCita()->getNombreServicio(),
                'cliente' => $cita->getClienteCita()->getNombreCliente(),
                'empleado' => $cita->getEmpleadoCita()->getNombreEmpleado(),
                'centro' => $cita->getCentroCita()->getNombreCentro(),
                'fecha' => $cita->getFechaCita()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($formattedCitas, Response::HTTP_OK);
    }

    #[Route('/client-dashboard/{id}', name: 'app_citas_client', methods: ['GET'])]
    public function showByClientId(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener el cliente por su ID
        $cliente = $entityManager->getRepository(Clientes::class)->find($id);

        if (!$cliente) {
            return new JsonResponse(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Obtener las citas asociadas a ese cliente
        $citas = $cliente->getClienteCita()->toArray();

        if (empty($citas)) {
            return new JsonResponse(['error' => 'No hay citas para este cliente'], Response::HTTP_NOT_FOUND);
        }

        $formattedCitas = [];
        foreach ($citas as $cita) {
            $formattedCitas[] = [
                'id' => $cita->getId(),
                'servicio' => $cita->getServicioCita()->getNombreServicio(),
                'cliente' => $cita->getClienteCita()->getNombreCliente(),
                'empleado' => $cita->getEmpleadoCita()->getNombreEmpleado(),
                'centro' => $cita->getCentroCita()->getNombreCentro(),
                'fecha' => $cita->getFechaCita()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($formattedCitas, Response::HTTP_OK);
    }

    #[Route('/admin-dashboard/{id}', name: 'app_citas_week', methods: ['GET'])]
    public function showByCenterIdNextWeek(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $startDate = new \DateTime();
        $endDate = new \DateTime('+7 days');

        $numCitas = $entityManager->getRepository(Citas::class)
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.id_centro = :id')
            ->andWhere('c.fecha_cita BETWEEN :start AND :end')
            ->setParameter('id', $id)
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getSingleScalarResult();

        $citas = $entityManager->getRepository(Citas::class)
            ->createQueryBuilder('c')
            ->where('c.id_centro = :id')
            ->andWhere('c.fecha_cita BETWEEN :start AND :end')
            ->setParameter('id', $id)
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('c.fecha_cita', 'DESC')
            ->getQuery()
            ->getResult();

        if (empty($citas)) {
            return new JsonResponse(['error' => 'No hay citas para este centro en los ultimos 7 dias'], Response::HTTP_NOT_FOUND);
        }

        $formattedCitas = [];
        foreach ($citas as $cita) {
            $formattedCitas[] = [
                'id' => $cita->getId(),
                'servicio' => $cita->getServicioCita()->getNombreServicio(),
                'cliente' => $cita->getClienteCita()->getNombreCliente(),
                'empleado' => $cita->getEmpleadoCita()->getNombreEmpleado(),
                'centro' => $cita->getCentroCita()->getNombreCentro(),
                'fecha' => $cita->getFechaCita()->format('Y-m-d H:i:s'),
            ];
        }

        $result = [
            'num_citas' => $numCitas,
            'citas' => $formattedCitas,
        ];

        return new JsonResponse($result, Response::HTTP_OK);
    }

    #[Route('/citas', name: 'app_insertar_citas', methods: ['POST'])]
    public function addCita(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //$citaData = $data['cita'];

        // Recuperar entidades relacionadas (servicio, cliente, empleado, centro)
        $servicio = $entityManager->getRepository(Servicios::class)->find($data['servicio']);
        $cliente = $entityManager->getRepository(Clientes::class)->find($data['cliente']);
        $empleado = $entityManager->getRepository(Empleados::class)->find($data['empleado']);
        $centro = $entityManager->getRepository(Centros::class)->find($data['centro']);

        $citaEntity = new Citas();
        $citaEntity->setServicioCita($servicio);
        $citaEntity->setClienteCita($cliente);
        $citaEntity->setEmpleadoCita($empleado);
        $citaEntity->setCentroCita($centro);
        $citaEntity->setFechaCita(new \DateTime($data['fecha']));

        $entityManager->persist($citaEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Cita insertada correctamente'], Response::HTTP_CREATED);
    }

    #[Route('/citas/{id}', name: 'app_actualizar_cita', methods: ['PATCH'])]
    public function updateCita(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $citaData = $data['cita'];

        // Recuperar la cita existente por su ID
        $citaEntity = $entityManager->getRepository(Citas::class)->find($id);

        if (!$citaEntity) {
            return new JsonResponse(['error' => 'Cita no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Actualizar las propiedades de la cita con los nuevos datos
        /*      if (isset($citaData['servicio'])) {
            $servicio = $entityManager->getRepository(Servicios::class)->find($citaData['servicio']);
            $citaEntity->setServicioCita($servicio);
        }

        if (isset($citaData['empleado'])) {
            $empleado = $entityManager->getRepository(Empleados::class)->find($citaData['empleado']);
            $citaEntity->setEmpleadoCita($empleado);
        } */

        if (isset($citaData['servicio'])) {
            // Buscar el servicio por nombre
            $servicio = $entityManager->getRepository(Servicios::class)->findOneByNombre($citaData['servicio']);
            if (!$servicio) {
                // Manejar el caso en que el servicio no se encuentra
                return new JsonResponse(['error' => 'Servicio no encontrado.'], Response::HTTP_NOT_FOUND);
            }
            $citaEntity->setServicioCita($servicio);
        }

        if (isset($citaData['empleado'])) {
            // Buscar el empleado por nombre
            $empleado = $entityManager->getRepository(Empleados::class)->findOneByNombre($citaData['empleado']);
            if (!$empleado) {
                // Manejar el caso en que el empleado no se encuentra
                return new JsonResponse(['error' => 'Empleado no encontrado.'], Response::HTTP_NOT_FOUND);
            }
            $citaEntity->setEmpleadoCita($empleado);
        }

        if (isset($citaData['cliente'])) {
            $cliente = $entityManager->getRepository(Clientes::class)->find($citaData['cliente']);
            $citaEntity->setClienteCita($cliente);
        }

        if (isset($citaData['centro'])) {
            $centro = $entityManager->getRepository(Centros::class)->find($citaData['centro']);
            $citaEntity->setCentroCita($centro);
        }

        if (isset($citaData['fecha'])) {
            $citaEntity->setFechaCita(new \DateTime($citaData['fecha']));
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Cita actualizada correctamente'], Response::HTTP_OK);
    }

    #[Route('/citas/{id}', name: 'app_eliminar_cita', methods: ['DELETE'])]
    public function deleteCita(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        // Recuperar la cita existente por su ID
        $citaEntity = $entityManager->getRepository(Citas::class)->find($id);

        if (!$citaEntity) {
            return new JsonResponse(['error' => 'Cita no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Eliminar la cita
        $entityManager->remove($citaEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Cita eliminada correctamente'], Response::HTTP_OK);
    }
}

/* {
    "cita": {
        "servicio": 1,
        "cliente": 1,
        "empleado": 1,
        "centro": 1,
        "fecha": "2024-01-01 00:00:00"
    }
} */