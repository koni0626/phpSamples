<?php
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    MailerInterface::class => DI\create(Mailer::class),
]);
$container = $containerBuilder->build();
