<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends AbstractController
{
   
    #[Route('/afficherBook',name:'app_afficher_book')]
public function affiche(BookRepository $repoBook):Response{
    $list=$repoBook->findAll();
    return $this->render('book/read.html.twig',['books'=>$list]);
}
#[Route('/AddBook',name:'book_add')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $book=new Book();
       
        $form = $this->createForm(BookType::class, $book);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            
          
        $em=$doctrine->getManager(); 
        $book->setEnabled(1);
        $nb=$book->getAuthor()->getNbBooks();
        $book->getAuthor()->setNbBooks($nb+1);  
        $em->persist($book); 
        $em->flush();
        return $this->redirectToRoute('app_afficher_book');
        }
             return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[route('/deletee/{id}',name:'app_delete_book')]
public function deletee(BookRepository $repoBook,int $id,EntityManagerInterface $entityManager):Response{
    $book=$repoBook->find($id);
    $entityManager->remove($book);
     $entityManager->flush();
    return $this->redirectToRoute('app_afficher_book');
}
   #[Route('/Updatee/{id}',name:'book_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,BookRepository $repoBook):response
    {
        $book=$repoBook->find($id);
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($request);
       if ($form->isSubmitted() )
       {
        $em=$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute('app_afficher_book');
    }
    return $this->render('book/update.html.twig',['form'=>$form->createView()]) ;
    
    }
}
