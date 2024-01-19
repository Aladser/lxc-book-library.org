<?php

namespace App\Controllers;

use App\Core\Controller;

class BookController extends Controller
{
    private string $csrf;
    private string $auth_user;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserController::getAuthUser();
        $this->csrf = Controller::createCSRFToken();
    }

    // индексная страница
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
