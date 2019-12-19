<?php

namespace App\DependencyInjection\Compiler;

use App\Manager\ScraperManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ScraperPass implements CompilerPassInterface
{
    const SERVICE_TAG_ID = 'app.scraper';
    const SERVICE_MANAGER = 'app.scraper.manager';

    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(self::SERVICE_MANAGER)) {
            throw new ServiceUnavailableHttpException('No service to load scrapers found');
        }

        $definition = $container->findDefinition(self::SERVICE_MANAGER);

        foreach ($container->findTaggedServiceIds(self::SERVICE_TAG_ID) as $id => $tags) {
            $definition->addMethodCall('addScraper', [new Reference($id)]);
        }
    }
}
