<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('dateReservation'),
            ChoiceField::new('etat')->setChoices([
                'En attente' => 'En attente',
                'Confirmée'  => 'CONFIRMEE',
                'Annulée'    => 'ANNULEE',
            ]),
            AssociationField::new('user')
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'email'),
            AssociationField::new('projection')
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'id'), // or custom label
        ];
    }
}
