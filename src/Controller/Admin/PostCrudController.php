<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Security\Voter\PostVoter;
use App\Utils\Transition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Workflow\WorkflowInterface;

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
            AssociationField::new('author')->onlyOnIndex(),
            ArrayField::new('status')
                ->setTemplatePath('fields/post_status.html.twig')
                ->onlyOnIndex(),
//            ChoiceField::new('status')
//                ->setChoices(fn() => ['draft' => 'draft', 'published' => 'published']),
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
        $workflow = $this->container->get('workflow.blog_publishing');
        $reviewRequestAction = Action::new(Transition::REVIEW_REQUEST, Transition::REVIEW_REQUEST)
            ->displayIf(fn($entity) => $workflow->can($entity, Transition::REVIEW_REQUEST))
            ->linkToCrudAction('reviewRequestAction');

        $editorReviewAction = Action::new(Transition::EDITOR_REVIEW, Transition::EDITOR_REVIEW)
            ->displayIf(fn($entity) => $workflow->can($entity, Transition::EDITOR_REVIEW)
//                && $this->isGranted('ROLE_EDITOR')
            )
            ->linkToCrudAction('editorReviewAction');

        $checkerCheckAction = Action::new(Transition::CHECKER_CHECK, Transition::CHECKER_CHECK)
            ->displayIf(fn($entity) => $workflow->can($entity, Transition::CHECKER_CHECK)
//                && $this->isGranted('ROLE_CHECKER')
            )
            ->linkToCrudAction('checkerCheckAction');

        $publishAction = Action::new(Transition::PUBLISH, Transition::PUBLISH)
            ->displayIf(fn($entity) => $workflow->can($entity, Transition::PUBLISH))
            ->linkToCrudAction('publishAction');

        return $actions->update(Crud::PAGE_INDEX, Action::EDIT,
            function (Action $action) {
                return $action->displayIf(fn($entity) => $this->isGranted(PostVoter::POST_OWNER_EDIT, $entity));
            })->update(Crud::PAGE_INDEX, Action::DELETE,
            function (Action $action) {
                return $action->displayIf(fn($entity) => $this->isGranted(PostVoter::POST_OWNER_DELETE, $entity));
            })
            ->add(Crud::PAGE_INDEX, $reviewRequestAction)
            ->add(Crud::PAGE_INDEX, $editorReviewAction)
            ->add(Crud::PAGE_INDEX, $checkerCheckAction)
            ->add(Crud::PAGE_INDEX, $publishAction);
    }

    public function reviewRequestAction(AdminContext $adminContext)
    {
        $post = $adminContext->getEntity()->getInstance();

        $this->applyTransition($post, Transition::REVIEW_REQUEST);

        return $this->redirect($this->generatePageUrl(PostCrudController::class, Action::INDEX));
    }

    public function editorReviewAction(AdminContext $adminContext)
    {
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        $post = $adminContext->getEntity()->getInstance();

        $this->applyTransition($post, Transition::EDITOR_REVIEW);

        return $this->redirect($this->generatePageUrl(PostCrudController::class, Action::INDEX));
    }

    public function checkerCheckAction(AdminContext $adminContext)
    {
        $this->denyAccessUnlessGranted('ROLE_CHECKER');
        $post = $adminContext->getEntity()->getInstance();

        $this->applyTransition($post, Transition::CHECKER_CHECK);

        return $this->redirect($this->generatePageUrl(PostCrudController::class, Action::INDEX));
    }

    public function publishAction(AdminContext $adminContext)
    {
        $post = $adminContext->getEntity()->getInstance();

        $this->applyTransition($post, Transition::PUBLISH);

        return $this->redirect($this->generatePageUrl(PostCrudController::class, Action::INDEX));
    }

    private function applyTransition($entity, $transitionName)
    {
        if ($entity instanceof Post) {
            $workflow = $this->container->get('workflow.blog_publishing');
            if ($workflow->can($entity, $transitionName)) {
                $workflow->apply($entity, $transitionName);
                $this->container->get('doctrine')->getManager()->flush();
            }
        }
    }

    private function generatePageUrl(string $crudControllerName, string $actionName)
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $adminUrlGenerator
            ->setController($crudControllerName)
            ->setAction($actionName)
            ->generateUrl();
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'workflow.blog_publishing' => '?' . WorkflowInterface::class
        ]);
    }
}
