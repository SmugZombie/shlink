<?php
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Application as CliApp;

/** @var ContainerInterface $container */
$container = include __DIR__ . '/../config/container.php';

/** @var CliApp $app */
$app = $container->get(CliApp::class);
$app->run();
