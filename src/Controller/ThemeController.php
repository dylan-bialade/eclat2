<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Theme;
use App\Form\ThemeType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'app_theme')]
    public function index(): Response
    {
        return $this->render('theme/index.html.twig', [
            'controller_name' => 'ThemeController',
        ]);
    }
    public function add(Request $request, EntityManagerInterface $entityManager): Response
{
    $theme = new Theme();
    $form = $this->createForm(ThemeType::class, $theme);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($theme);
        $entityManager->flush();

        return $this->redirectToRoute('theme_list');
    }

    return $this->render('theme/add.html.twig', [
        'form' => $form->createView(),
    ]);
}
public function list(EntityManagerInterface $entityManager): Response
{
    $themes = $entityManager->getRepository(Theme::class)->findAll();

    return $this->render('theme/list.html.twig', [
        'themes' => $themes,
    ]);
}

}
