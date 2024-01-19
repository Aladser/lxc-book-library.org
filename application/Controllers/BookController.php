<?php

namespace App\Controllers;

use App\Core\Controller;

class BookController extends Controller
{
    private string $csrf;
    private string $authUser;

    public function __construct()
    {
        parent::__construct();
        $this->authUser = UserController::getAuthUser();
        $this->csrf = Controller::createCSRFToken();
    }

    public function index(mixed $args): void
    {
        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'index_view.php',
            content_css: 'index.css',
        );
    }
}
