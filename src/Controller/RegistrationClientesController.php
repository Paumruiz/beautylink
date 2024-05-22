<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Clientes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationClientesFormType;
use App\Repository\CentrosRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationClientesController extends AbstractController
{

    #[Route('/register_clientes', name: 'app_register_clientes', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $centrosRepository = $entityManager->getRepository(Centros::class);

        $cliente = new Clientes();

        $cliente->setPassword($data['password_cliente']);
        $cliente->setNombreCliente($data['nombre_cliente']);
        $cliente->setApellidosCliente($data['apellidos_cliente']);
        $cliente->setTelefonoCliente($data['telefono_cliente']);
        $cliente->setEmailCliente($data['email_cliente']);
        $centro = $centrosRepository->find($data['id_centro']);
        $cliente->setIdCentroCliente($centro);

        $errors = $validator->validate($cliente);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($cliente);
        $entityManager->flush();


        return new JsonResponse(['status' => 'Cliente registrado con éxito!'], Response::HTTP_CREATED);
    }
}
