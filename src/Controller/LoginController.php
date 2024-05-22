<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Clientes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginController extends AbstractController
{
    #[Route('/login_centros', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email_centro'] ?? null;
        $password = $data['password_centro'] ?? null;

        if (!$email || !$password) {
            return $this->json(['success' => false, 'error' => 'Datos de inicio de sesión incompletos'], 400);
        }

        $userRepository = $entityManager->getRepository(Centros::class);
        $user = $userRepository->findOneBy(['email_centro' => $email]);
        $centroId = $user->getId();


        if (!$user || !$this->checkPassword($user, $password)) {
            return $this->json(['success' => false, 'error' => 'Credenciales inválidas'], 400);
        }


        $nombreCentro = $user->getNombreCentro();
        $emailCentro = $user->getEmailCentro();

        return $this->json(['success' => true, 'centroId' => $centroId, 'nombre_centro' => $nombreCentro, 'email_centro' => $emailCentro]);
    }

    private function checkPassword($user, $password): bool
    {
        return $user->getPassword() === $password;
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
    }
}
