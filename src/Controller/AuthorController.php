<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
}}
