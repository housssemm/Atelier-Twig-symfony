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

    #[Route('/afficherBook', name: 'app_afficher_book')]
    public function affiche(BookRepository $repoBook): Response
    {
        $list = $repoBook->findAll();
        $nb = $repoBook->getNbRomance();
        return $this->render('book/read.html.twig', ['books' => $list, 'nb' => $nb]);
    }
    #[Route('/AddBook', name: 'book_add')]
    public function ajouter(ManagerRegistry $doctrine, Request $request): response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $doctrine->getManager();
            $book->setEnabled(1);
            $nb = $book->getAuthor()->getNbBooks();
            $book->getAuthor()->setNbBooks($nb + 1);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_afficher_book');
        }
        return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[route('/deletee/{id}', name: 'app_delete_book')]
    public function deletee(BookRepository $repoBook, int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $repoBook->find($id);
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('app_afficher_book');
    }
    #[Route('/Updatee/{id}', name: 'book_update')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, BookRepository $repoBook): response
    {
        $book = $repoBook->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_afficher_book');
        }
        return $this->render('book/update.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/trie', name: 'trier')]
    public function trie(BookRepository $rb): Response
    {
        $list = $rb->trie();


        return $this->render('book/read.html.twig', ['books' => $list]);
    }
    #[Route('/filtre', name: 'filtre')]
    public function filtre(BookRepository $rb): Response
    {
        $list = $rb->pub();


        return $this->render('book/read.html.twig', ['books' => $list]);
    }
    #[Route('/affByid', name: 'rechercheByID')]
    public function affId(BookRepository $repoBoo, Request $req): Response
    {
        $id = $req->get('id');
        $list = $repoBoo->findById($id);
        return $this->render('book/read.html.twig', ['books' => $list]);
    }
    #[Route('/affB', name: 'rechercheB')]
    public function booksListByAuthors(BookRepository $repoBoo, Request $req): Response
    {
        $id = $req->get('id');
        $list = $repoBoo->findById($id);
        return $this->render('book/read.html.twig', ['books' => $list]);
    }
}
