<?php

namespace Neutron\Plugin\PageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronPageExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        if ($config['enable'] === false){
            $container->getDefinition('neutron_page.plugin')->clearTag('neutron.plugin');
            return;
        }

        $container->setAlias('neutron_page.controller.backend.page', $config['page_controller_backend']);
        $container->setAlias('neutron_page.controller.backend.administration', $config['administration_controller']);
        $container->setAlias('neutron_page.controller.frontend.page', $config['page_controller_frontend']);
        $container->setAlias('neutron_page.page_manager', $config['page_manager']);
        $container->setParameter('neutron_page.page_class', $config['page_class']);
        $container->setParameter('neutron_page.templates', $config['templates']);
        $container->setParameter('neutron_page.translation_domain', $config['translation_domain']);
        $container->setParameter('neutron_page.page_grid', $config['page_grid']);
 
        
        $container->setAlias('neutron_page.form.handler.page', $config['form']['handler']);
        $container->setParameter('neutron_page.form.type.page', $config['form']['type']);
        $container->setParameter('neutron_page.form.name.page', $config['form']['name']);
    }
}
