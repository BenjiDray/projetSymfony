<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Content;
use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo=$this->getDoctrine()->getRepository(Article::class);
        $article=$repo->findAll();       
         return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$article
            ]);
    }


    /**
     * @route("/",name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig',[
            'title' =>"bienvenue les poto",
            'age'=> 1
            ]);
    }


    /**
     * @route("/blog/new",name="create_blog")
     * @route("/blog/{id}/edit",name="edit_blog")
     */

    public function create(Article $article = null,Request $request, ObjectManager $manager)
    {
        if(!$article)
        {
            $article=new Article();
        }
        
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }

        return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editButton'=>$article->getId() !==null
        ]);
    }

    /**
     *
     * @route("/blog/{id}",name="blog_show")
     */
    public function show($id,Request $request, ObjectManager $manager)
    {
       
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article =$repo->find($id);     
        return $this->render('blog/show.html.twig',
        [
            
            'article'=>$article,
            
        ]);
    }

}
