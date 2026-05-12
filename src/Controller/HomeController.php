<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\ProjectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        FilmRepository       $filmRepository,
        ProjectionRepository $projectionRepository
    ): Response {
        // All films from DB
        $films = $filmRepository->findAll();

        // Total projections count from DB
        $totalProjections = count($projectionRepository->findAll());

        // Unique genres from DB (no duplicates)
        $genres = [];
        foreach ($films as $film) {
            if ($film->getGenre() && !in_array($film->getGenre(), $genres)) {
                $genres[] = $film->getGenre();
            }
        }

        return $this->render('home/index.html.twig', [
            'films'            => $films,
            'totalProjections' => $totalProjections,
            'genres'           => $genres,
        ]);
    }
}
