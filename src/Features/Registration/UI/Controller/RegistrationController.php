<?php

declare(strict_types=1);

namespace App\Features\Registration\UI\Controller;

use App\Features\Registration\Application\DTO\RegistrationDTO;
use App\Features\Registration\Application\Exception\EmailAlreadyExistsException;
use App\Features\Registration\Application\Service\RegistrationService;
use App\Features\Registration\UI\Form\RegistrationFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly RegistrationService $registrationService,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new RegistrationDTO();
        $form = $this->createForm(RegistrationFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $dto->setPlainPassword($plainPassword);
            $dto->setConfirmPassword($plainPassword);

            try {
                $this->registrationService->register($dto);

                $this->addFlash('success', 'registration.success');

                return $this->redirectToRoute('app_register_success');
            } catch (EmailAlreadyExistsException) {
                $this->addFlash('error', 'registration.email.already_exists');
            } catch (\Throwable $e) {
                $this->logger->error('Unexpected error during registration', [
                    'email' => $dto->getEmail(),
                    'exception' => $e->getMessage(),
                ]);
                $this->addFlash('error', 'registration.error.unexpected');
            }
        }

        return $this->render('@Registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}
