<?php

namespace App\Controller;

use App\Entity\Commentaire;
use DateTime;
use App\Entity\Study;
use App\Search\Search;
use DateTimeInterface;
use App\Form\StudyType;
use App\Entity\Planning;
use App\Form\SearchType;
use App\Entity\Formation;
use App\Form\PlannigType;
use App\Form\PlanningType;
use App\Form\FormationType;
use App\Form\CommentaireType;
use App\Form\EditStudyType;
use App\Repository\StudyRepository;
use App\Repository\PlanningRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends AbstractController
{
    private $formationrepository;
    private $studyrepository;
    public $i;

    public function __construct(FormationRepository $formationrepository, StudyRepository $studyrepository)
    {
        $this->formationrepository = $formationrepository;
        $this->studyrepository = $studyrepository;
    }
    /**
     * @Route("/home", name="home")
     */
    public function index(EntityManagerInterface $em, PlanningRepository $repo, Request $request, FormationRepository $repoFor)
    {
        $month = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        $formation = new Formation();
        $planning = new Planning();
        $study = new Study();
        $search = new Search();
        $commentaire = new Commentaire();
        //Creation des formulaires
        $form = $this->createForm(PlanningType::class, $planning);
        $form_ = $this->createForm(FormationType::class, $formation);
        $form_study = $this->createForm(StudyType::class, $study);
        $form_formation = $this->createForm(SearchType::class, $search);
        $form_comment = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);
        $form_->handleRequest($request);
        $form_study->handleRequest($request);
        $form_formation->handleRequest($request);
        $form_comment->handleRequest($request);
        
        //formulaire pour les formations
        if ($form_->isSubmitted() && $form_->isValid()) {
            $em->persist($formation);
            $em->flush();
        }
        //formulaire pour les plannings
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($planning);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        //formulaire pour les élèves
        if ($form_study->isSubmitted() && $form_study->isValid()) {
            $em->persist($study);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        //formulaire pour les les commentaire
        if ($form_study->isSubmitted() && $form_study->isValid()) {
            $temps = new \DateTime(date($_POST['temps']));
            $commentaire->setTemps($temps);
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        //récupération des valeurs de la base de donnée
        $name_formation = $this->formationrepository->findAll();
        $list_study  = $this->studyrepository->findStudy($search);
        $items = $repo->findPlanning($search);
        
        //retourne une vue
        return $this->render('home/index.html.twig', [
            'items' => $items,
            'name_formation' => $name_formation,
            'month' => $month,
            'form' => $form->createView(),
            'form_' => $form_->createView(),
            'form_study' => $form_study->createView(),
            'form_formation' => $form_formation->createView(),
            'form_comment' => $form_comment->createView(),
            'list_study' => $list_study
        ]);
    }

    /**
     * @Route("/home/{id}", name="edit", requirements={"id":"\d+"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Planning $planning, PlanningRepository $repo)
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //Permet d'éditer l'élément de la base de donnée Planning
            $em->persist($planning);
            $em->flush();
            return $this->redirectToRoute('home');
        }
     // requête ajax  pour l'edition des plannings
        if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'content' =>  $this->renderView('home/edit.html.twig',['form' => $form->createView(),'planning' => $planning])
            ]);
        }

        return $this->render('home/edit.html.twig', [
            'form' => $form->createView(),
            'planning' => $planning,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $em, Planning $planning,Request $request,PlanningRepository $repository)
    {
        $items = $repository->findAll();
        /*if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'content' =>  $this->renderView('home/delete.html.twig',['id' => $id])
            ]);
        }*/
        $em->remove($planning);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/task/{id}",name="task")
     */
    public function task(PlanningRepository $repository,$id,Request $request)
    {
        $items = $repository->findAll();
        if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'content' =>  $this->renderView('home/delete.html.twig',['id' => $id])
            ]);
        }
        return $this->render('home/delete.html.twig',compact('id'));
    }

    /**
     * @Route("/home/study/{id}", name="study")
     */
    public function editStudy(Study $study,Request $request,EntityManagerInterface $em)
    {
        $form = $this->createForm(EditStudyType::class,$study);
        $form->handleRequest($request);
        if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'content'=>$this->renderView('home/edit_study.html.twig',['form' => $form->createView()])
            ]);
        }
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($study);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('home/edit_study.html.twig',[
            'form'=>$form->createView(),

        ]);
    }
}
