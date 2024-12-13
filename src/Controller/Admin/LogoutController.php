<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/admin/logout', 'logout')]
    public function logout()
    {
        // Route gérée par Symfony via le fichier security.yaml
        // pour effectuer la déconnexion
    }
}