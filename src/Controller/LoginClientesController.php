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


/* class LoginClientesController extends AbstractController
{
    #[Route('/login_clientes', name: 'app_login_clientes')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(LoginClientesFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $email = $formData['email_cliente'];
            $password = $formData['password_cliente'];

            $userRepository = $entityManager->getRepository(Clientes::class);
            $user = $userRepository->findOneBy(['email_cliente' => $email]);


            if ($user && $user->getPassword() === $password) {

                if ($user instanceof Centros) {
                    $user->setRoles(['ROLE_CENTRO']);
                } elseif ($user instanceof Clientes) {
                    $user->setRoles(['ROLE_CLIENTE']);
                    $clientId = $user->getId();
                    return $this->redirectToRoute('app_citas_client', ['id' => $clientId]);
                }
            }


            $error = 'Correo electrónico o contraseña incorrectos.';
        } else {

            $error = $authenticationUtils->getLastAuthenticationError();
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login_clientes/index.html.twig', [
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

class LoginClientesController extends AbstractController
{
    #[Route('/login_clientes', name: 'app_login_clientes', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Decodificar el JSON de la solicitud
        $data = json_decode($request->getContent(), true);

        // Validar los datos recibidos
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

        // Lógica para asignar roles si es necesario
        $user->setRoles(['ROLE_CLIENTE']);
        $clientId = $user->getId();
        $centroId = $user->getIdCentroCliente()->getId();
        $nombreCliente = $user->getNombreCliente();
        $apellidosCliente = $user->getApellidosCliente();
        $emailCliente = $user->getEmailCliente();

        // Autenticación exitosa
        return $this->json(['success' => true, 'clientId' => $clientId, 'centroId' => $centroId, 'nombre_cliente' => $nombreCliente, 'apellidos_cliente' => $apellidosCliente, 'email_cliente' => $emailCliente]);
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
