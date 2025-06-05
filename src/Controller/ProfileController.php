<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    #[Route('/new', name: 'app_profile', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        // Lấy người dùng đăng nhập hiện tại
        $securityUser = $this->getUser();

        if (!$securityUser) {
            $this->addFlash('error', 'Bạn cần đăng nhập để truy cập trang này!');
            return $this->redirectToRoute('app_login');
        }

        dump(get_class($securityUser));
        dump($securityUser instanceof UserInterface ? "Implements UserInterface" : "Does not implement UserInterface");
        

        $identifier = $securityUser->getUserIdentifier();
        dump("User identifier: " . $identifier);
        

        $user = $userRepository->findOneBy(['email' => $identifier]);
        

        if (!$user && method_exists($securityUser, 'getUsername')) {
            $username = $securityUser->getUsername();
            dump("Username: " . $username);
            $user = $userRepository->findOneBy(['username' => $username]);
        }
        

        if ($user) {
            dump("Found user with ID: " . $user->getId());
            dump("User full name: " . ($user->getFullName() ?? 'Not set'));
        } else {
            dump("No matching user found in database");
        }
        
        $allUsers = $userRepository->findAll();
        dump("Total users in database: " . count($allUsers));
        foreach ($allUsers as $idx => $u) {
            if ($idx < 5) {
                dump([
                    'id' => $u->getId(),
                    'email' => $u->getEmail(),
                    'fullName' => $u->getFullName() ?? 'Not set'
                ]);
            }
        }

        if (!$user) {
            $user = new User();
            $user->setEmail($identifier);
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Thông tin đã được cập nhật!');
            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
