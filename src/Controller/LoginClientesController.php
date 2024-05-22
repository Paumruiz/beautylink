<?php

namespace App\Controller;

use App\Entity\Centros;
use App\Entity\Clientes;
use App\Form\LoginClientesFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginClientesController extends AbstractController
{
    #[Route('/login_clientes', name: 'app_login_clientes', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email_cliente'] ?? null;
        $password = $data['password_cliente'] ?? null;

        if (!$email || !$password) {
            return $this->json(['success' => false, 'error' => 'Datos de inicio de sesión incompletos'], 400);
        }

        $userRepository = $entityManager->getRepository(Clientes::class);
        $user = $userRepository->findOneBy(['email_cliente' => $email]);

        if (!$user || !$this->checkPassword($user, $password)) {
            return $this->json(['success' => false, 'error' => 'Credenciales inválidas'], 400);
        }

        $user->setRoles(['ROLE_CLIENTE']);
        $clientId = $user->getId();
        $centroId = $user->getIdCentroCliente()->getId();
        $nombreCliente = $user->getNombreCliente();
        $apellidosCliente = $user->getApellidosCliente();
        $emailCliente = $user->getEmailCliente();

        return $this->json(['success' => true, 'clientId' => $clientId, 'centroId' => $centroId, 'nombre_cliente' => $nombreCliente, 'apellidos_cliente' => $apellidosCliente, 'email_cliente' => $emailCliente]);
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
