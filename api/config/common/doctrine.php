<?php

declare(strict_types=1);

use App\Auth;

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\DBAL\Types\Type;

return [
    EntityManagerInterface::class => function (ContainerInterface $container): EntityManagerInterface {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{
         *     metadata_dirs:array,
         *     dev_mode:bool,
         *     proxy_dir:string,
         *     cache_dir:?string,
         *     types:array<string,string>,
         *     connection:array
         * } $settings
         */
        $settings = $container->get('config')['doctrine'];

        $config = Setup::createAnnotationMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $settings['cache_dir'] ?
                DoctrineProvider::wrap(new FilesystemAdapter('', 0, $settings['cache_dir'])) :
                DoctrineProvider::wrap(new ArrayAdapter()),
            false
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        foreach ($settings['types'] as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        return EntityManager::create(
            $settings['connection'],
            $config
        );
    },

    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => 'chat_api-postgtres_1',  //getenv('DB_HOST'),
                'user' => 'app',    //getenv('DB_USER'),
                'password' => 'secret', //getenv('DB_PASSWORD'),
                'dbname' => 'app',  //getenv('DB_NAME'),
                'charset' => 'utf-8'
            ],
            'metadata_dirs' => [
                __DIR__.'/../../src/Auth/Entity',
            ],
            'types' => [
                Auth\Entity\User\IdType::NAME => Auth\Entity\User\IdType::class,
                Auth\Entity\User\RoleType::NAME => Auth\Entity\User\RoleType::class,
                Auth\Entity\User\StatusType::NAME => Auth\Entity\User\StatusType::class,
                Auth\Entity\User\EmailType::NAME => Auth\Entity\User\EmailType::class,
            ],
        ],
    ],
];