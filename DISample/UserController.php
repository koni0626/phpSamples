<?php
// UserController.php
class UserController {
  private $mailer;
  
  public function __construct(MailerInterface $mailer) {
      $this->mailer = $mailer;
  }

  public function sendWelcomeEmail(string $email): bool {
      $subject = "Welcome to our platform!";
      $body = "Thank you for signing up!";
      return $this->mailer->send($email, $subject, $body);
  }
}
