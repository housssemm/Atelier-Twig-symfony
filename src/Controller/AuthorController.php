<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showAuthor/{name}',name:'auth')]
    public function showAuthor(string $name):Response
    {
            return $this->render('service/show.html.twig',['name'=>$name]);

    }
    #[Route('/showList',name:'list')]
    public function listAuthors():Response{
    $authors = array(
    array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
    array('id' => 2, 'picture' => '/images/william.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
    array('id' => 3, 'picture' => '/images/Taha-Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);
return $this->render('service/list.html.twig',['authors'=>$authors]);


}

    #[Route('/showAuth/{id}',name:'auther')]
public function authorDetails($id):Response{
     $authors = array(
    array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
    array('id' => 2, 'picture' => '/images/william.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
    array('id' => 3, 'picture' => '/images/Taha-Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);
     return $this->render('service/showAuthor.html.twig', [
            'id' => $id,'authors'=>$authors]);
            

}
#[Route('/afficher',name:'app_afficher')]
public function affiche(AuthorRepository $repoAuthor):Response{
    $list=$repoAuthor->findAll();
    return $this->render('author/read.html.twig',['authors'=>$list]);
}
#[route('/delete{id}',name:'app_delete')]
public function delete(AuthorRepository $repoAuthor,int $id,EntityManagerInterface $entityManager):Response{
    $auth=$repoAuthor->find($id);
    $entityManager->remove($auth);
     $entityManager->flush();
    return $this->redirectToRoute('app_afficher');
}

// #[Route('/Add',name:'author_add')]
//     public function ajouter(ManagerRegistry $doctrine):response
//     {
//         $author=new Author(); //nouveau objet author
//         $author->setUsername("m"); // remplir attribut usename
//         $author->setEmail("m@esprit.tn");// remplir attribut email
//         $em=$doctrine->getManager(); // appel entity manager
//         $em->persist($author); // insert into
//         $em->flush();// d'envoyer tout ce qui a été persisté avant à la base de données
//         return $this->redirectToRoute('app_afficher');
//     }


#[Route('/Add',name:'author_add')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $author=new Author();
       
        $form = $this->createForm(AuthorType::class, $author);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            
          
        $em=$doctrine->getManager(); 
        $em->persist($author); 
        $em->flush();
        return $this->redirectToRoute('app_afficher');
        }
             return $this->render('author/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
        #[Route('/Update/{id}',name:'book_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,AuthorRepository $repoAuthor):response
    {
        $author=$repoAuthor->find($id);
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
       if ($form->isSubmitted() )
       {
        $em=$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute('app_afficher');
    }
    return $this->render('author/update.html.twig',['form'=>$form->createView()]) ;
    
    }
}
