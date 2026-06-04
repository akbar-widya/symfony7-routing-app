<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateBlogPostDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

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

    #[Route('/blog/create', name: 'blog_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateBlogPostDto $blogDto
    ): Response
    {
        $newTitle = $blogDto-> title;
        $newContent = $blogDto->content;

        // Data is not persisted — stored in memory only for this request.
        // To save permanently, a database integration is needed.
        return new Response('Blog post "' . $newTitle . '" created successfully!');
    }

    #[Route('/blog/log-example', name: 'blog_log', methods: ['GET'])]
    public function logAction(LoggerInterface $logger): Response
    {
        // Symfony automatically injects logger service
        $logger->info('The blog log action was successfully accessed.');

        return new Response('Action logged successfully.');
    }

    #[Route('/blog/log/first', name: 'blog_first', methods: ['GET'])]
    public function firstAction(): Response
    {
        $this->logger->info('First action accessed.');
        return new Response('First action completed.');
    }

    #[Route('/blog/log/second', name: 'blog_second', methods: ['GET'])]
    public function secondAction(): Response
    {
        $this->logger->info('Second action accessed.');
        return new Response('Second action completed.');
    }

    #[Route('/blog/submit', name: 'blog_submit', methods: ['POST'])]
    public function submit(): Response
    {
        // Internal logic to process the form submission and save to the database goes here...

        // Log the business event behind the scenes
        $this->logger->info('Data submission processed through /blog/submit path (Route Name: blog_submit).');

        // Add a flash message to the session
        $this->addFlash('success', 'Your blog post was created successfully!');

        // Safely redirect user to different page to prevent duplicate submissions
        return $this->redirectToRoute('blog_index');
    }
}
