<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;


    /**
     * @Route("/category", name="category.")
     */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param CategoryRepository  $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){

        $category=new Category();
        $form=$this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted()){
             $em=$this->getDoctrine()->getManager();
        
                $em->persist($category);
                $em->flush(); 
                return $this->redirect($this->generateUrl('category.index'));
           }
    
    
        return $this->render('category/create.html.twig',[
            'form' =>$form->createView()
        ]);
    }

          /**
     * @Route("/show/{id}", name="show")
     * @param Category $category
     * @return Response
     */

    public function show(Category $category){
      
       return $this->render('category/show.html.twig', [
            'category' => $category
        ]);  
    }

       /**
     * @Route("/delete/{id}", name="delete")
     * @param Category $category
     * @return Response
     */

     public function remove(Category $category){

        $em=$this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success','category was removed');

        return $this->redirect($this->generateUrl('category.index'));
    }

      /**
     * @Route("/edit/{id}", name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request,$id){

        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $form=$this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted()){
             $em=$this->getDoctrine()->getManager();
        
                $em->persist($category);
                $em->flush(); 
                return $this->redirect($this->generateUrl('category.index'));
           }
    
    
        return $this->render('category/update.html.twig',[
            'form' =>$form->createView()
        ]);
    }
    
    
}
