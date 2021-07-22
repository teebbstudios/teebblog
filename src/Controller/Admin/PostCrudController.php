<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Security\Voter\PostVoter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            ImageField::new('postImage')
                ->setBasePath($this->getParameter('base_path'))
                ->setUploadDir($this->getParameter('upload_dir'))
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]'),
            TextareaField::new('summary'),
            TextEditorField::new('body'),
            AssociationField::new('author'),
            ChoiceField::new('status')
                ->setChoices(fn() => ['draft' => 'draft', 'published' => 'published']),
            TimeField::new('createdAt')
                ->setFormat('Y-MM-dd HH:mm:ss')->onlyOnIndex(),
            TimeField::new('updatedAt')
                ->setFormat('Y-MM-dd HH:mm:ss')->onlyOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['title', 'summary', 'body']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(ChoiceFilter::new('status')
            ->setChoices(['draft' => 'draft', 'published' => 'published']));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->update(Crud::PAGE_INDEX, Action::EDIT,
            function (Action $action) {
                return $action->displayIf(fn($entity) => $this->isGranted(PostVoter::POST_OWNER_EDIT, $entity));
            })->update(Crud::PAGE_INDEX, Action::DELETE,
            function (Action $action) {
                return $action->displayIf(fn($entity) => $this->isGranted(PostVoter::POST_OWNER_DELETE, $entity));
            });
    }
}
