<?php

namespace App\Controller;

use App\Service\JsonPlaceholderApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(HttpClientInterface $httpClientInterface): Response
    {
        $jsonPAM = new JsonPlaceholderApiManager($httpClientInterface);

        $response = $jsonPAM->postList()->getContent();

        return $this->render('blog/index.html.twig', [
            'postList' => json_decode($response, true),
        ]);
    }

    #[Route('/blog/post/{id}', name: 'post_blog')]
    public function showPost(HttpClientInterface $httpClientInterface, int $id): Response
    {
        $jsonPAM = new JsonPlaceholderApiManager($httpClientInterface);

        $postInfo = json_decode($jsonPAM->postInfo($id)->getContent(), true);
        $userInfo = json_decode($jsonPAM->userInfo($postInfo['userId'])->getContent(), true);

        return $this->render('blog/postDetails.html.twig', [
            'postInfo' => $postInfo,
            'userInfo' => $userInfo
        ]);
    }

    #[Route('/blog/createForm', name: 'create_blog')]
    public function showCreatePost(): Response
    {
        return $this->render('blog/postForm.html.twig');
    }

    #[Route('/blog/create', name: 'create_post')]
    public function createPost(HttpClientInterface $httpClientInterface, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $jsonPAM = new JsonPlaceholderApiManager($httpClientInterface);

        $params = [
            'userId' => rand(1, 10),
            'id'     => 101,
            'title'  => $request->request->get('title'),
            'body'   => $request->request->get('body')
        ];

        $response = json_decode($jsonPAM->createPost($params)->getContent());

        var_dump($response);

        return $this->redirectToRoute('app_blog');
    }
}
