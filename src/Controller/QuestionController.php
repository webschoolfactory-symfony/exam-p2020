<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends Controller
{
    /**
     * @Route(path="/", methods={"GET"}, name="questions_list")
     */
    public function indexAction()
    {
        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

        return $this->render(
            'Question/index.html.twig',
            [ 'questions' => $questions ]
        );
    }

    /**
     * @Route(path="/questions/{id}", methods={"GET"}, name="questions_show", requirements={"id": "\d+"})
     *
     * @param Question $question
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Question $question)
    {
        return $this->render('Question/show.html.twig', [ 'question' => $question ]);
    }

    /**
     * @Route(path="/questions/new", methods={"GET"}, name="questions_new")
     * @Route(path="/questions", methods={"POST"}, name="questions_create")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(QuestionType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $question = $form->getData();
            $this->getDoctrine()->getManager()->persist($question);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questions_list');
        }

        return $this->render(
            'Question/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
