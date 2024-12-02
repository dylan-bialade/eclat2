<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
    #[Route('/utilisateur/add', name: 'app_utilisateur_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
{
    $utilisateur = new Utilisateur();
    $form = $this->createForm(UtilisateurType::class, $utilisateur);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        
        return $this->redirectToRoute('utilisateur_list');
    }

    return $this->render('utilisateur/add.html.twig', [
        'form' => $form->createView(),
    ]);
}
    #[Route('/utilisateur/list', name: 'app_utilisateur_list')]
    public function list(EntityManagerInterface $entityManager): Response
{
        $utilisateurs = $entityManager->getRepository(Utilisateur::class)->findAll();

    return $this->render('utilisateur/list.html.twig', [
        'utilisateurs' => $utilisateurs,
    ]);
}
#[Route('/utilisateur/delete/{id}', name: 'app_utilisateur_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
{
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);

        if ($utilisateur) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

    return $this->redirectToRoute('utilisateur_list');
}
    #[Route('/utilisateur/update/{id}', name: 'app_utilisateur_update')]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('utilisateur_list');
        }

    return $this->render('utilisateur/update.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
