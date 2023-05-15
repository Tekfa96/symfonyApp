<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $data=$doctrine->getRepository(Car::class)->findAll();

        return $this->render('main/index.html.twig', [
            'list'=>$data
        ]);
    }

/**
 * @Route("create", name="create") 
 * */
#[Route('/create', name: 'create')]

public function create(Request $request,  PersistenceManagerRegistry $doctrine){
    $car=new Car();
    $form=$this->createForm(CarType::class,$car);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($car);
        $em->flush();
        $this->addFlash('notice','Submitted successfully !!!');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('main/create.html.twig',[
        'form'=> $form->createView()
    ]);
}


/**
 * @Route("/update/{id}", name="update") 
 * */
#[Route('/update/{id}', name: 'update')]

public function update(Request $request, $id, PersistenceManagerRegistry $doctrine){
    $car=$doctrine->getRepository(Car::class)->find($id);
    $form=$this->createForm(CarType::class,$car);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($car);
        $em->flush();
        $this->addFlash('notice','Update successfully !!!');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('main/update.html.twig',[
        'form'=> $form->createView()
    ]);
}

/**
 * @Route("/delete/{id}", name="delete") 
 * */
#[Route('/delete/{id}', name: 'delete')]

public function delete($id, PersistenceManagerRegistry $doctrine){
    $data=$doctrine->getRepository(Car::class)->find($id);
    $em=$doctrine->getManager();
    $em->remove($data);
    $em->flush();
    $this->addFlash('notice','Deleted successfully !!!');
    return $this->redirectToRoute('app_main');    
}
}