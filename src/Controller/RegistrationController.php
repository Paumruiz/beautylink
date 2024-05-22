<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $centro = new Centros();
        $centro->setNombreCentro($data['nombre_centro']);
        $centro->setDireccionCentro($data['direccion_centro']);
        $centro->setTelefonoCentro($data['telefono_centro']);
        $centro->setEmailCentro($data['email_centro']);
        $centro->setPassword($data['password_centro']);

        $errors = $validator->validate($centro);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['error' => $errorsString], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($centro);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Centro registrado con éxito!'], Response::HTTP_CREATED);
    }
}
