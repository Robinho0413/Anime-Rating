<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\Rating;
use App\Form\AnimeType;
use App\Form\RatingType;
use App\Repository\AnimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/anime')]
final class AnimeController extends AbstractController
{
    #[Route(name: 'app_anime_index', methods: ['GET'])]
    public function index(AnimeRepository $animeRepository): Response
    {
        return $this->render('anime/index.html.twig', [
            'animes' => $animeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_anime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $anime = new Anime();
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($anime);
            $entityManager->flush();

            return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anime/new.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

#[Route('/{id}', name: 'app_anime_show', methods: ['GET', 'POST'])]
public function show(
    Anime $anime,
    Request $request,
    EntityManagerInterface $em
): Response {
    $ratingForm = null;

    if ($this->getUser()) {
        // Vérifier si l'utilisateur a déjà noté cet anime
        $rating = $em->getRepository(Rating::class)->findOneBy([
            'user' => $this->getUser(),
            'anime' => $anime,
        ]);

        if (!$rating) {
            $rating = new Rating();
            $rating->setUser($this->getUser());
            $rating->setAnime($anime);
            $rating->setCreatedAt(new \DateTime());
        }

        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rating);
            $em->flush();

            return $this->redirectToRoute('app_anime_show', [
                'id' => $anime->getId(),
            ]);
        }

        $ratingForm = $form->createView();
    }

    return $this->render('anime/show.html.twig', [
        'anime' => $anime,
        'ratingForm' => $ratingForm,
    ]);
}

    #[Route('/{id}/edit', name: 'app_anime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Anime $anime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anime/edit.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_anime_delete', methods: ['POST'])]
    public function delete(Request $request, Anime $anime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anime->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($anime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
    }
}
