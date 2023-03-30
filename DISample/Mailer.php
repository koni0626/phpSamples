<?php
interface MailerInterface {
    public function send(string $recipient, string $subject, string $body): bool;
}


class Mailer implements MailerInterface {
    public function send(string $recipient, string $subject, string $body): bool {
        // ここでメール送信処理を実装
        return true;
    }
}
