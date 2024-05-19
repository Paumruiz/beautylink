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

/* class RegistrationController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function main()
    {
        return $this->render(view: 'base.html.twig');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Centros();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($form->get('plainPassword')->getData());
            // Set other centro properties
            $user->setNombreCentro($form->get('nombre_centro')->getData());
            $user->setDireccionCentro($form->get('direccion_centro')->getData());
            $user->setTelefonoCentro($form->get('telefono_centro')->getData());

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute(route: 'test');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
 */

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
        //$password = $passwordHasher->hashPassword($centro, $data['password_centro']);
        //$centro->setPassword($password);
        $centro->setPassword($data['password_centro']);

        // Validación
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
