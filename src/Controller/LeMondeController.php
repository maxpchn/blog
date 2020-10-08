<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LeMondeController extends AbstractController
{
    /**
     * @Route("/le/monde", name="leMonde")
     */
    public function index()
    {
        return $this->render('le_monde/index.html.twig', [
            'controller_name' => 'LeMondeController',
        ]);
    }

    /**
     * @Route("/le/monde/home", name="leMonde_home")
     */
    public function home(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $repository = $em->getRepository(Article::class);
        $donnees = $repository->findAll();

        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('le_monde/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/le/monde/view/{id}", name="leMonde_view")
     */
    public function view(Article $article, EntityManagerInterface $manager, Request $request): Response
    {
        $comment = new Comment();
        $user = $this->getUser();

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $comment->setUser($user);
            $comment->setAuthor($user->getPseudo());
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('leMonde_view', ['id' => $article->getId()]);
        }

        return $this->render('le_monde/view.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
    /**
     * permet de créer un article
     *@Route("le/monde/create", name="lemonde_create")
     *@Route("le/monde/{id}/edit", name="lemonde_edit")
     *@IsGranted("ROLE_USER")
     * @return void
     */
    public function form(Article $article = null, EntityManagerInterface $manager, Request $request)
    {
        if (!($article)) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush($article);

            return $this->redirectToRoute('leMonde_view', ['id' => $article->getId()]);
        }

        return $this->render('le_monde/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => !($article->getId() == null)
        ]);
    }
}
