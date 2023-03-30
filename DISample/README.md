DI（Dependency Injection）コンテナとは、依存性を注入するためのコンテナで、オブジェクトの作成と管理を行います。これにより、コードのモジュール性、再利用性、テスト容易性が向上します。PHPでDIコンテナを使ったプログラムを書く方法について、サンプルコードを元に説明します。

まず、PHPでDIコンテナを使用するために、一般的なPSR-11互換のコンテナライブラリであるPHP-DIをインストールしましょう。

```
composer require php-di/php-di
```

次に、サンプルコードを用意します。ここでは、メール送信機能を持つMailerクラスと、その機能を利用するUserControllerクラスを例にして説明します。

```
// Mailer.php
interface MailerInterface {
    public function send(string $recipient, string $subject, string $body): bool;
}

class Mailer implements MailerInterface {
    public function send(string $recipient, string $subject, string $body): bool {
        // ここでメール送信処理を実装
        return true;
    }
}

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
```

この例では、UserControllerはMailerのインスタンスに依存しています。DIコンテナを使用することで、この依存関係を解決し、コードのモジュール性を向上させます。

PHP-DIを使用してDIコンテナを設定します。

```
// container.php
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    MailerInterface::class => DI\create(Mailer::class),
]);

$container = $containerBuilder->build();
```

上記のコードでは、MailerInterfaceを実装するMailerクラスのインスタンスを作成する定義をDIコンテナに追加しています。

最後に、UserControllerのインスタンスを作成し、メール送信機能をテストします。

```
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
```

上で説明した方法により、DIコンテナを使って依存関係を解決し、コードの可読性とメンテナンス性が向上します。さらに、テストのしやすさも向上させることができます。以下に、UserControllerのテストを作成する方法を説明します。

まず、PHPUnitをインストールします。

```
composer require --dev phpunit/phpunit
```

次に、MailerInterfaceのモックオブジェクトを作成し、UserControllerのテストを行います。


```
// tests/UserControllerTest.php
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase {
    public function testSendWelcomeEmail() {
        // MailerInterfaceのモックオブジェクトを作成
        $mailerMock = $this->createMock(MailerInterface::class);

        // sendメソッドが呼び出されたときの振る舞いを定義
        $mailerMock->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('test@example.com'),
                $this->stringContains('Welcome'),
                $this->stringContains('Thank you')
            )
            ->willReturn(true);

        // UserControllerのインスタンスを作成
        $userController = new UserController($mailerMock);

        // sendWelcomeEmailメソッドをテスト
        $isEmailSent = $userController->sendWelcomeEmail("test@example.com");

        $this->assertTrue($isEmailSent);
    }
}

```

上記のコードでは、MailerInterfaceのモックオブジェクトを作成し、その振る舞いを定義しています。これにより、実際のメール送信処理を行わずに、UserControllerのsendWelcomeEmailメソッドをテストすることができます。
