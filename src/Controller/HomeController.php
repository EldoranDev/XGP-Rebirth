<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\HomeTabsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[AsController]
class HomeController extends AbstractController
{
    #[Route('/')]
    public function indexAction(): Response
    {
        $registrationForm = $this->createForm(RegistrationFormType::class, null, [
            'action' => $this->generateUrl('app_register'),
        ]);

        return $this->render('home.html.twig', [
            'registrationForm' => $registrationForm,
        ]);
    }

    #[Route('/home/{tab}', requirements: ['tab' => new EnumRequirement(HomeTabsEnum::class)])]
    public function homeContentAction(string $tab): Response
    {
        return $this->render("home/tabs/{$tab}.html.twig");
    }
}
