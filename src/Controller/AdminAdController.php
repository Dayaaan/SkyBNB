<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Service\Pagination;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page": "\d+"})
     */
    public function index(AdRepository $repo, $page = 1, Pagination $pagination)
    {
        // requirements={"page": "\d+"} = page de type number seulement et mettre $page = 1 dans la function
        // equivalent a {page<\d+>?1} --- le ?1 permet de dire que c'est optionnel et 1 valeur par default 
        // plus besoin de mettre $page = 1 dans la fonction
        // $limit = 10;
        // $start = $page * $limit - $limit;
        // (page 1): 1 * 10 = 10 - 10 = 0
        // (page 2): 2 * 10 = 20 - 10 = 10
        // $total = count($repo->findAll());

        // $pages = ceil($total / $limit); // 3.4 => 4 pages;
        $pagination->setEntityClass(Ad::class)
                  //->setRoute('admin_ads_index')
                   ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            // 'ads' => $repo->findBy([],[], $limit, $start),
            // 'pages' => $pages,
            // 'page' => $page,
            'pagination' => $pagination,
        ]);
        // Méthode find : permet de retrouver un enregistrement par son identfiant

        // $ad = $repo->find(417);
        // $ad = $repo->findOneBy([
        //     'title' => 'Titré modifié',
        // ]);
        // dump($ad);

        // $ads = $repo->findBy([],[],5,0);
        // dump($ads);
        // return $this->render('admin/ad/index.html.twig', [
        //     'ads' => $repo->findAll()
        // ]);
    }
    /**
     * Permet d'afficher le formulaire d'éditition
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistré"
            );
        };
        
        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Permet de supprimé une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        if(count($ad->getBookings()) > 0 ) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède deja des réservations "
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }
        

        return $this->redirectToRoute("admin_ads_index");
    }
}
