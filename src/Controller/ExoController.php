<?php

namespace App\Controller;


use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\ArticleRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExoController extends AbstractController
{
    #[Route('/exo', name: 'app_exo')]
    public function index(): Response
    {
        return $this->render('exo/index.html.twig', [
            'controller_name' => 'ExoController',
        ]);
    }

    #[route('voitures', name: 'voitures')]

    public function voitures()
    {
        $voiture = "R5";
        $description = "Une petite voiture cher";
        $prix = 1600;

        return $this->render('exo/voitures.html.twig',[
            'voiture' => $voiture,
            'desc'=> $description,
            'prix'=> $prix
        ]);
    } 
    #[route('/voiture/list', name:'voiture_liste')]
    public  function liste(VoitureRepository $repo)
    {
        $voitures = $repo->findAll();
        return $this->render('exo/liste.html.twig',[
            'voitures'=> $voitures
        ]);
    }

    #[Route('/voitures/new',name: 'voiture_new')]
    #[Route('/voitures/edit{id}',name: 'voiture_edit')]

    public function form(EntityManagerInterface $manager, Request $globals,Voiture $voiture=null)
    {
    if ($voiture == null)
    {
        $voiture= new Voiture;
    }
    $form= $this->createForm(VoitureType::class,$voiture);
    $form->handleRequest($globals);
    
    if($form->isSubmitted() && $form->isValid()){
        
    $manager->persist($voiture);
    $manager->flush();
    return $this->redirectToRoute("voiture_liste");
    }
    return $this->renderForm('exo/form.html.twig', [
        'form'=> $form,
        'editMode'=> $voiture->getId() !== null
    ]);


}
   
#[Route("/voiture/delete{id}",name : "voiture_delete")]
public function delete(Voiture $voiture, EntityManagerInterface $manager)
{
        $manager->remove($voiture);
        $manager->flush();
        return $this->redirectToRoute('voiture_liste');

}
    
}




