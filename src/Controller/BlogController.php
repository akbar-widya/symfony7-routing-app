<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
//    /blog render list
    #[Route('/blog', name: 'blog_index', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('blog/blog.html.twig', []);
    }

//    /blog/{id} detail satu post
    #[Route('/blog/{id}', name: 'blog_details', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function details(int $id): Response
    {
        $posts = [
            1 => ['title' => 'Bangkok Trip', 'content' => 'Finally touched down in Bangkok after a 3-hour flight. The heat hit immediately — sticky, dense, and unforgiving. Spent the first day just wandering Chatuchak market, eating everything in sight. Mango sticky rice at every corner. No regrets.'],
            2 => ['title' => 'Graduation Day', 'content' => 'Four years of late nights, cold coffee, and last-minute submissions — all condensed into one morning in a rented gown. When they called my name, I blanked. Walked across the stage on autopilot. Only felt it later, sitting in the parking lot with my family, photos done, gown half-off.'],
            3 => ['title' => 'Hiring Result Announcement', 'content' => 'The email came at 7.43am. I had been refreshing my inbox since 6. One line that mattered, buried after two paragraphs of corporate warmth: "We are pleased to offer you the position." Sat there for a full minute before it registered.'],
        ];

        $post = $posts[$id];

        return $this->render('blog/details.html.twig', [
            'post' => $post,
        ]);
    }

//    /blog/author/{name} nama author
    #[Route('/blog/author/{name}', name: 'blog_author', methods: ['GET'])]
    public function author(string $name): Response
    {
        $authors = [
            'alfafa' => [
                'name' => 'Alfafa',
                'bio' => 'A software engineering student who writes about travel, life milestones, and the occasional existential crisis.',
                'location' => 'Purwokerto, Indonesia',
                'joined' => 'March 2023',
            ],
            'raihan' => [
                'name' => 'Raihan',
                'bio' => 'Mostly writes about food and bad decisions. Sometimes both at the same time.',
                'location' => 'Yogyakarta, Indonesia',
                'joined' => 'July 2023',
            ],
        ];

        $author = $authors[strtolower($name)];

        return $this->render('blog/author.html.twig', [
            'author' => $author,
        ]);
    }
}
