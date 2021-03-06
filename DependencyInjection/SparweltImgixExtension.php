<?php

namespace Sparwelt\ImgixBundle\DependencyInjection;

use Sparwelt\ImgixBundle\Twig\ImgixTwigExtension;
use Sparwelt\ImgixLib\ImgixService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Federico Infanti <federico.infanti@sparwelt.de>
 *
 * @since  24.07.18 10:24
 */
class SparweltImgixExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(ImgixService::class);
        $definition->replaceArgument(0, $config['cdn_configurations']);
        $definition->replaceArgument(1, $config['image_filters']);

        $definition = $container->getDefinition(ImgixTwigExtension::class);
        $definition->replaceArgument(1, $config['logger']);
        $definition->replaceArgument(2, $config['log_level']);
    }
}
