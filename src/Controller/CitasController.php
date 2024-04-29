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

        $citaData = $data['cita'];

        // Recuperar entidades relacionadas (servicio, cliente, empleado, centro)
        $servicio = $entityManager->getRepository(Servicios::class)->find($citaData['servicio']);
        $cliente = $entityManager->getRepository(Clientes::class)->find($citaData['cliente']);
        $empleado = $entityManager->getRepository(Empleados::class)->find($citaData['empleado']);
        $centro = $entityManager->getRepository(Centros::class)->find($citaData['centro']);

        $citaEntity = new Citas();
        $citaEntity->setServicioCita($servicio);
        $citaEntity->setClienteCita($cliente);
        $citaEntity->setEmpleadoCita($empleado);
        $citaEntity->setCentroCita($centro);
        $citaEntity->setFechaCita(new \DateTime($citaData['fecha']));

        $entityManager->persist($citaEntity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Cita insertada correctamente'], Response::HTTP_CREATED);
    }
}
