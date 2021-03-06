<?php
declare(strict_types=1);

namespace Shlinkio\Shlink;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor;
use const PHP_EOL;

return [

    'logger' => [
        'formatters' => [
            'dashed' => [
                'format' => '[%datetime%] %channel%.%level_name% - %message%' . PHP_EOL,
                'include_stacktraces' => true,
            ],
        ],

        'handlers' => [
            'shlink_rotating_handler' => [
                'class' => RotatingFileHandler::class,
                'level' => Logger::INFO,
                'filename' => 'data/log/shlink_log.log',
                'max_files' => 30,
                'formatter' => 'dashed',
            ],
            'swoole_access_handler' => [
                'class' => StreamHandler::class,
                'level' => Logger::INFO,
                'stream' => 'php://stdout',
            ],
        ],

        'processors' => [
            'exception_with_new_line' => [
                'class' => Common\Logger\Processor\ExceptionWithNewLineProcessor::class,
            ],
            'psr3' => [
                'class' => Processor\PsrLogMessageProcessor::class,
            ],
        ],

        'loggers' => [
            'Shlink' => [
                'handlers' => ['shlink_rotating_handler'],
                'processors' => ['exception_with_new_line', 'psr3'],
            ],
            'Swoole' => [
                'handlers' => ['swoole_access_handler'],
                'processors' => ['psr3'],
            ],
        ],
    ],

    'dependencies' => [
        'factories' => [
            'Logger_Shlink' => Common\Factory\LoggerFactory::class,
            'Logger_Swoole' => Common\Factory\LoggerFactory::class,
        ],
    ],

    'zend-expressive-swoole' => [
        'swoole-http-server' => [
            'logger' => [
                'logger-name' => 'Logger_Swoole',
            ],
        ],
    ],

];
