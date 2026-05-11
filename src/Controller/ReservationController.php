<?php

// ─────────────────────────────────────────────────────────────────────────────
// REPLACE your existing ReservationController.php
// Path: src/Controller/ReservationController.php
// ─────────────────────────────────────────────────────────────────────────────

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ProjectionRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    // ── List: user sees ONLY their own reservations from DB ─────────────────
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        // Filter by logged-in user — only their reservations
        $reservations = $reservationRepository->findBy(
            ['user' => $this->getUser()],
            ['dateReservation' => 'DESC']           // newest first
        );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,  // real data from DB
        ]);
    }

    // ── New: create a reservation for a projection ──────────────────────────
    #[Route('/{projectionId}', name: 'app_reservation_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(int $projectionId,Request $request, EntityManagerInterface $em, ProjectionRepository $projectionRepository): Response
    {
        $projection = $projectionRepository->find($projectionId);
        $projection = $projectionId ? $projectionRepository->find($projectionId) : null;

        if (!$projection) {
            $this->addFlash('danger', 'Projection introuvable.');
            return $this->redirectToRoute('app_home');
        }

        // ✅ Use getNbPlaces() – note the capital 'B'
        if ($projection->getNbPlaces() <= 0) {
            $this->addFlash('danger', 'Cette projection est complète.');
            return $this->redirectToRoute('app_film_show', ['id' => $projection->getFilm()->getId()]);
        }

        $reservation = new Reservation();
        $reservation->setDateReservation(new \DateTime());
        $reservation->setEtat('En attente');
        $reservation->setUser($this->getUser());
        $reservation->setProjection($projection);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projection->setNbPlaces($projection->getNbPlaces() - 1);

            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', '🎟️ Réservation effectuée avec succès !');
            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'projection' => $projection,
        ]);
    }

    // ── Delete / Cancel ──────────────────────────────────────────────────────
    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $em
    ): Response {
        // Security: only the owner can cancel
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
            // Restore the place in DB
            $projection = $reservation->getProjection();
            $projection->setNbPlaces($projection->getNbPlaces() + 1);

            $em->remove($reservation);
            $em->flush();

            $this->addFlash('warning', 'Réservation annulée.');
        }

        return $this->redirectToRoute('app_reservation_index');
    }
}
