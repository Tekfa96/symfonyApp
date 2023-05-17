<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Entity\Driver;
use App\Form\DriverType;
use App\Entity\Reservation;
use App\Form\ReservationType;
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
#[Route('/create_car', name: 'create')]

public function createCar(Request $request,  PersistenceManagerRegistry $doctrine){
    $car=new Car();
    $form=$this->createForm(CarType::class,$car);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($car);
        $em->flush();
        $this->addFlash('notice','Car created successfully !!!');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('main/car.html.twig',[
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
        $this->addFlash('notice','Updated successfully !!!');
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


//Driver creation

#[Route('/create_driver', name: 'create_driver')]

public function createDriver(Request $request,  PersistenceManagerRegistry $doctrine){
    $driver=new Driver();
    $form=$this->createForm(DriverType::class,$driver);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($driver);
        $em->flush();
        $this->addFlash('notice','Driver created successfully !!!');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('main/driver.html.twig',[
        'form'=> $form->createView()
    ]);
}


#[Route('/create_reservation', name: 'create_reservation')]

public function createReservation(Request $request,  PersistenceManagerRegistry $doctrine){
    $reservation=new Reservation();
    $form=$this->createForm(ReservationType::class,$reservation);
    $form->handleRequest($request);
    if($form->isSubmitted()&& $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($reservation);
        $em->flush();
        $this->addFlash('notice','Reservation created successfully !!!');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('main/reservation.html.twig',[
        'form'=> $form->createView()
    ]);
}




}