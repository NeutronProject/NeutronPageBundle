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

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        
        $this->addGeneralConfigurations($rootNode);
        
        $this->addTemplatesConfiguration($rootNode);
        
        $this->addMediaConfiguration($rootNode);
        
        $this->addFormSection($rootNode);

        return $treeBuilder;
    }
    
    private function addGeneralConfigurations(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('page_class')->defaultValue('Neutron\Plugin\PageBundle\Entity\Page')->end()
                ->scalarNode('page_image_class')->defaultValue('Neutron\Plugin\PageBundle\Entity\PageImage')->end()
                ->scalarNode('page_controller')->defaultValue('neutron_page.controller.page.default')->end()
                ->scalarNode('page_manager')->defaultValue('neutron_page.manager.page.default')->end()
            ->end()
        ;
    }
    
    private function addTemplatesConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('templates')
                ->defaultValue(array('standard' => 'template.standard'))
                ->validate()
                    ->ifTrue(function($v){return empty($v);})
                    ->thenInvalid('You should provide at least one template.')
                ->end()
                ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()
                
            ->end()
        ;
    }
    
    private function addMediaConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('media')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('html_editor')
                        ->isRequired(true)
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('security')
                                ->defaultValue(array('ROLE_SUPER_ADMIN'))
                                ->validate()
                                    ->ifTrue(function($v){
                                        return !is_array($v);
                                    })
                                    ->thenInvalid('Config "security must be array."')
                                ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('page_image')
                        ->isRequired(true)
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('minWidth')->defaultValue(800)->end()
                                ->scalarNode('minHeight')->defaultValue(300)->end()
                                ->scalarNode('extensions')->defaultValue('jpeg,jpg')->end()
                                ->scalarNode('maxSize')->defaultValue('2M')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                
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
                            ->scalarNode('type')->defaultValue('neutron_page_form_page')->end()
                            ->scalarNode('handler')->defaultValue('neutron_page.form.handler.page.default')->end()
                            ->scalarNode('name')->defaultValue('neutron_page')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
