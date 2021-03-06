<?php

namespace EzLandingPageLayoutsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Yaml;
/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EzLandingPageLayoutsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
      * {@inheritdoc}
      */
     public function prepend(ContainerBuilder $container)
     {
         //load default_layouts.yml
         $configLayoutFile = __DIR__ . '/../Resources/config/default_layouts.yml';
         $config = Yaml::parse(file_get_contents($configLayoutFile));
         $container->prependExtensionConfig('ez_systems_landing_page_field_type', $config);
         $container->addResource(new FileResource($configLayoutFile));

         // load ezplatform.yml
         $config = Yaml::parse( __DIR__ . '/../Resources/config/ezplatform.yml' );
         $container->prependExtensionConfig( 'ezpublish', $config );

         //Add to config to allow read access
         $container->prependExtensionConfig( 'assetic', array( 'bundles' => array( 'EzLandingPageLayoutsBundle' ) ) );

     }

}
