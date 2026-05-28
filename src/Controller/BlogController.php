<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_list')]
    public function list(): Response
    {
//    ...
    }

//    Restricting Method -> HTTP method
    #[Route('/api/posts/{id}', methods: ['GET', 'HEAD'])]
    public function show(int $id): Response { /* ... */ }

    #[Route('/api/posts/{id}', methods: ['PUT'])]
    public function edit(int $id): Response { /* ... */ }

//    Parameter -> {} dynamic path
    #[Route('/blog/{slug}', name: 'blog_show')]
//    not just curl braces, your method also receive a parameter
    public function show(string $slug): Response {
//        /blog/my-first-post
//        $slug = 'my-first-post'
    }

//    Parameter with requirement (usually use when only accept number, such as $id)
    #[Route('/blog/{page}', name: 'blog_list', requirements: ['page' => '\d+'])]    // /blog/2
    public function list(int $page): Response  { /* ... */ }

    #[Route('/blog/{slug}', name: 'blog_show')]     // /blog/my-first-post
    public function show(string $slug): Response { /* ... */ }

//    Optional parameter
    #[Route('/blog/{page}', name: 'blog_list', requirements: ['page' => '\Dd+'])]
//    Set a default value your method will receive
    public function list(int $page = 1): Response  { /* ... */ }
}
