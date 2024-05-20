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


/* class LoginController extends AbstractController
{
    #[Route('/login_centros', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $email = $formData['email_centro'];
            $password = $formData['password_centro'];

            $userRepository = $entityManager->getRepository(Centros::class);
            $user = $userRepository->findOneBy(['email_centro' => $email]);


            if ($user && $user->getPassword() === $password) {

                if ($user instanceof Centros) {
                    $user->setRoles(['ROLE_CENTRO']);
                } elseif ($user instanceof Clientes) {
                    $user->setRoles(['ROLE_CLIENTE']);
                }

                return $this->redirectToRoute('app_empleados');
            }


            return $this->redirectToRoute('app_login');
        } else {

            $error = $authenticationUtils->getLastAuthenticationError();
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'loginForm' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
    }
}
 */


class LoginController extends AbstractController
{
    #[Route('/login_centros', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Decodificar el JSON de la solicitud
        $data = json_decode($request->getContent(), true);

        // Validar los datos recibidos
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

        // Lógica para asignar roles si es necesario

        // Autenticación exitosa
        return $this->json(['success' => true, 'centroId' => $centroId]);
    }

    private function checkPassword($user, $password): bool
    {
        return $user->getPassword() === $password;
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // Lógica de logout si es necesaria
    }
}
