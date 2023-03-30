<?php
// index.php
require_once '..\vendor\autoload.php';
require_once "Mailer.php";
require_once "UserController.php";
require_once "container.php";

$userController = $container->get(UserController::class);    //ここがミソ

$isEmailSent = $userController->sendWelcomeEmail("test@example.com");

if ($isEmailSent) {
    echo "Welcome email sent successfully!";
} else {
    echo "Failed to send welcome email.";
}
