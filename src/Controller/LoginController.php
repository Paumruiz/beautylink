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
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(LoginFormType::class);
        var_dump($request->request->all());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email_centro')->getData();
            $password = $form->get('password_centro')->getData();


            $userRepository = $entityManager->getRepository(Centros::class);
            $user = $userRepository->findOneByEmail($email);


            if ($user && $user->getPassword() === $password) {

                if ($user instanceof Centros) {
                    $user->setRoles(['ROLE_CENTRO']);
                } elseif ($user instanceof Clientes) {
                    $user->setRoles(['ROLE_CLIENTE']);
                }
                var_dump($user);



                return $this->redirectToRoute('app_empleados');
            }


            $error = 'Correo electrónico o contraseña incorrectos.';
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
