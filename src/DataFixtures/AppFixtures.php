<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Projection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $filmsData = [
            ['titre' => 'Les Étoiles du Silence', 'genre' => 'Drame', 'dateCreation' => '2022-02-14'],
            ['titre' => 'Nuit au Cinéma', 'genre' => 'Thriller', 'dateCreation' => '2021-10-07'],
            ['titre' => "Lumiere d'Hiver", 'genre' => 'Romance', 'dateCreation' => '2023-01-18'],
            ['titre' => "Course contre l'Ombre", 'genre' => 'Action', 'dateCreation' => '2020-06-30'],
            ['titre' => 'Le Dernier Projectionniste', 'genre' => 'Aventure', 'dateCreation' => '2024-03-11'],
        ];

        $films = [];

        foreach ($filmsData as $filmData) {
            $film = (new Film())
                ->setTitre($filmData['titre'])
                ->setGenre($filmData['genre'])
                ->setDateCreation(new \DateTime($filmData['dateCreation']));

            $manager->persist($film);
            $films[] = $film;
        }

        $projectionsData = [
            ['film' => 0, 'dateProjection' => '2026-05-15', 'salle' => 'Salle 1', 'nbPlaces' => 120],
            ['film' => 0, 'dateProjection' => '2026-05-18', 'salle' => 'Salle 2', 'nbPlaces' => 90],
            ['film' => 1, 'dateProjection' => '2026-05-16', 'salle' => 'Salle 3', 'nbPlaces' => 80],
            ['film' => 1, 'dateProjection' => '2026-05-20', 'salle' => 'Salle 1', 'nbPlaces' => 120],
            ['film' => 2, 'dateProjection' => '2026-05-17', 'salle' => 'Salle 2', 'nbPlaces' => 90],
            ['film' => 3, 'dateProjection' => '2026-05-19', 'salle' => 'Salle 4', 'nbPlaces' => 60],
            ['film' => 4, 'dateProjection' => '2026-05-21', 'salle' => 'Salle 5', 'nbPlaces' => 50],
        ];

        foreach ($projectionsData as $projectionData) {
            $projection = (new Projection())
                ->setFilm($films[$projectionData['film']])
                ->setDateProjection(new \DateTime($projectionData['dateProjection']))
                ->setSalle($projectionData['salle'])
                ->setNbPlaces($projectionData['nbPlaces']);

            $manager->persist($projection);
        }

        $manager->flush();
    }
}
