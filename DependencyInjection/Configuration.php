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
        
        $this->addTemplatesConfiguration($rootNode);
        
        $this->addFormSection($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultFalse()->end()
                ->scalarNode('page_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('page_instance_controller')->defaultValue('neutron_page.controller.backend.page_instance.default')->end()
                ->scalarNode('administration_controller')->defaultValue('neutron_page.controller.backend.administration.default')->end()
                ->scalarNode('front_controller')->defaultValue('neutron_page.controller.front.default')->end()
                ->scalarNode('page_manager')->defaultValue('neutron_page.doctrine.page_manager.default')->end()
                ->scalarNode('translation_domain')->defaultValue('NeutronPageBundle')->end()
                ->scalarNode('page_grid')->defaultValue('page_management')->end()
            ->end()
        ;
    }
    
    private function addTemplatesConfiguration(ArrayNodeDefinition $node)
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
    
    
    private function addFormSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                 ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('neutron_page_page_instance')->end()
                            ->scalarNode('instance_type')->defaultValue('neutron_page_page_instance_instance')->end()
                            ->scalarNode('handler')->defaultValue('neutron_page.form.handler.page_instance.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_page_page_instance')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
