<?php

namespace App\Controller\Admin;

use App\Entity\Projection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projection::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('dateProjection'),
            TextField::new('salle'),
            IntegerField::new('nbPlaces'),
            AssociationField::new('film')   // ← 🟢 MUST be present
            ->setRequired(true)
                ->setFormTypeOption('choice_label', 'titre'), // shows film title
        ];
    }
}
