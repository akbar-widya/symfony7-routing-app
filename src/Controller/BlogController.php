<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class BlogController extends AbstractController
{
//    /blog render list
    #[Route('/blog', name: 'blog_index', methods: ['GET'])]
    public function show(
        #[MapQueryParameter] string $category = null,
    ): Response
    {
        $posts = [
            1 => ['title' => 'Bangkok Trip', 'category' => 'travel'],
            2 => ['title' => 'Graduation Day', 'category' => 'life'],
            3 => ['title' => 'Hiring Result Announcement', 'category' => 'life'],
        ];

        if ($category !== null) {
            $posts = array_filter($posts, fn($post) => $post['category'] === $category);
        }

        return $this->render('blog/blog.html.twig', [
            'category' => $category,
            'posts' => $posts,
        ]);
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

        if (!isset($posts[$id])) {
            // Throw the 404 exception if the post is missing
            throw $this->createNotFoundException('The requested blog post does not exist.');
        }

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
                'location' => 'Surabaya, Indonesia',
                'joined' => 'March 2023',
            ],
            'raihan' => [
                'name' => 'Raihan',
                'bio' => 'Mostly writes about food and bad decisions. Sometimes both at the same time.',
                'location' => 'Jakarta, Indonesia',
                'joined' => 'July 2023',
            ],
        ];

        if (!isset($authors[$name])) {
            throw $this->createNotFoundException('The requested blog author does not exist.');
        }

        $author = $authors[strtolower($name)];

        return $this->render('blog/author.html.twig', [
            'author' => $author,
        ]);
    }
}
