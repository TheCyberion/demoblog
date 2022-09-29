<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    
    #[route('/', name:'home')]
    public function home()
    {return $this->render('blog/home.html.twig', [
        'slogan' => "on va réussir la team!",
        'age' =>'32',
    ]);}
    //Pour envoyer des variables depusi le conttroller, la méthodes render() prend en 2eme arg un tableau associatif




    #[Route('/blog', name: 'app_blog')]
    //une route est définie par 2 arguments: son chemin (/blog) et son nom (/app_blog)
    // aller sur unenroute permet de lancer la méthode qui se trouve directement en dessous 
    
    public function index(ArticleRepository $repo): Response
    //pour récupérer le repository, je le passe en arg de la methode index()
    //cela s'appelle une injection de dépendance
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'articles'=> $articles //
        ]);
        
        //render()permet d'afficher le contenu d'un template
    }


    #[Route('/blog/show/{id}', name:"blog_show")]
    public function show($id, ArticleRepository $repo) //$id correspond a {id} dans l'url
    {
        $article = $repo->find($id);
        //find() permet de récupérer 1 article en fonction de son id

        return $this->render('blog/show.html.twig', [
            'item'=> $article
        ]);
    }


    #[Route("/blog/new",name:"blog_create")]
    #[route("/blog/edit/{id}",name:"blog_edit")]
    public function from(Request $globals, EntityManagerInterface $manager, Article $article = null)
    {
        // la classe Request contient les données véhiculées par les superglobales ($_POST, $_GET, $_SERVER,...)
    if($article== null)
    {
        $article = new Article; //je crée un objet de la classe  Article vide prêt a être rempli
        $article->setCreatedAt0(new \DateTime); //ajout de la date de création à l'insertion d'un article
        // Si l'article est null, nous sommes dans al route blog_create : nous devons créer un nouvel article
        //Sinon, $article n'est pas null, nous somme sur la route blog_edit : nous récupérons l'article correspondant à l'id

    }

        $form = $this->createForm(ArticleType::class, $article); // je lie le formulaire à mon objet $article
        // createForm() permet de récupérer un formulaire
        
        $form->handleRequest($globals);

        //dump($globals); //permet d'afficher les données de l'objet $globals (comme var_dump())
        //dump($article);

        if($form->isSubmitted() && $form->isValid())
        {
           
            $manager->persist($article); //prepare l'insertion d'article en bdd
            $manager->flush(); //éxectute la requête d'insertion

            return $this->redirectToRoute('blog_show', [
                'id'=> $article->getId()
            ]);
            // cette méthode peret de rediriger la page de notre article  nouvellement crée
        }



        return $this->renderForm("blog/form.html.twig",[
            'formArticle' => $form,
            'editMode' => $article->getId() !== null
        ]);
    }

    #[Route("/blog/delete/{id}", name:"blog_delete")]
    
        public function delete($id,EntityManagerInterface $manager, ArticleRepository $repo)

        { $article = $repo->find($id);

        $manager->remove($article); //prépare la suppression
        $manager->flush(); //execute la suppression
        
        
        return $this->redirectToRoute('app_blog');  //redirection vers le liste des articles 

            
    }








}
