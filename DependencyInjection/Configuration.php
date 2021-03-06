<?php

namespace Neutron\Plugin\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_page');
        
        $this->addGeneralConfigurations($rootNode);
        
        $this->addTemplatesConfigurations($rootNode);
        
        $this->addFormConfigurations($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultFalse()->end()
                ->scalarNode('page_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('page_controller_backend')->defaultValue('neutron_page.controller.backend.page.default')->end()
                ->scalarNode('page_controller_frontend')->defaultValue('neutron_page.controller.frontend.page.default')->end()
                ->scalarNode('page_manager')->defaultValue('neutron_page.doctrine.page_manager.default')->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronPageBundle')->end()
                ->scalarNode('page_datagrid')->defaultValue('page_management')->end()
            ->end()
        ;
    }
    
    private function addTemplatesConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('templates')->isRequired()
                ->validate()
                    ->ifTrue(function($v){return empty($v);})
                    ->thenInvalid('You should provide at least one template.')
                ->end()
                ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end() 
                ->cannotBeOverwritten()
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
        ;
    }
    
    
    private function addFormConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                 ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_page')->end()
                            ->scalarNode('handler')->defaultValue('neutron_page.form.handler.page.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_page')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
