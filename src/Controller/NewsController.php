<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;



 /**
     * @Route("/news", name="news.")
     */

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param NewsRepository  $newsRepository
     * @return Response
     */
    public function index(NewsRepository $newsRepository)
    {
        $news = $newsRepository->findAll();
        return $this->render('news/index.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){

        $news=new News();
        $form=$this->createForm(NewsType::class,$news);
        $form->handleRequest($request);
        if($form->isSubmitted()){
           $em=$this->getDoctrine()->getManager();
           /**@var UploadedFile $file */
           $file=$request->files->get('news')['attachment'];
           if($file){
               $filename=md5(uniqid()) . '.' . $file->guessClientExtension();
                $file->move(
                $this->getParameter('uploads_dir'),
                $filename
                );
                $news->setImage($filename);
                $em->persist($news);
                $em->flush(); 
           }
       
            return $this->redirect($this->generateUrl('news.index'));
        }
       
        return $this->render('news/create.html.twig',[
            'form' =>$form->createView()
        ]);

    }

       /**
     * @Route("/show/{id}", name="show")
     * @param News $news
     * @return Response
     */

    public function show(News $news){
      
       return $this->render('news/show.html.twig', [
            'news' => $news
        ]);  
    }

     /**
     * @Route("/delete/{id}", name="delete")
     * @param News $news
     * @return Response
     */

     public function remove(News $news){

        $em=$this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();
        $this->addFlash('success','news was removed');

        return $this->redirect($this->generateUrl('news.index'));
    }

      /**
     * @Route("/edit/{id}", name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request,$id){

        $News=$this->getDoctrine()->getRepository(News::class)->find($id);
        $form=$this->createForm(CategoryType::class,$News);
        $form->handleRequest($request);
        if($form->isSubmitted()){
             $em=$this->getDoctrine()->getManager();
        
                $em->persist($News);
                $em->flush(); 
                return $this->redirect($this->generateUrl('News.index'));
           }
    
    
        return $this->render('News/update.html.twig',[
            'form' =>$form->createView()
        ]);
    }
}
