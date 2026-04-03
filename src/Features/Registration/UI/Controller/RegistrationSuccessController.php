<?php

declare(strict_types=1);

namespace App\Features\Registration\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class RegistrationSuccessController extends AbstractController
{
    #[Route('/register/success', name: 'app_register_success', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@Registration/register_success.html.twig');
    }
}
