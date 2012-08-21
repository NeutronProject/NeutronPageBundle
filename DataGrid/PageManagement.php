<?php
namespace Neutron\Plugin\PageBundle\DataGrid;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\ORM\Query;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Doctrine\ORM\EntityManager;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class PageManagement
{

    protected $factory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    protected $translator;
    
    protected $router;
    
    protected $session;
    
    protected $pageClass;
    
    protected $defaultLocale;

    /**
     * 
     * @param FactoryInterface $factory
     * @param EntityManager $em
     * @param Translator $translator
     * @param Router $router
     * @param SessionInterface $session
     * @param string $categoryClass
     * @param string $pageClass
     */
    public function __construct (FactoryInterface $factory, EntityManager $em, 
            Translator $translator, Router $router, SessionInterface $session, $pageClass, $defaultLocale)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->translator = $translator;
        $this->router = $router;
        $this->session = $session;
        $this->pageClass = $pageClass;
        $this->defaultLocale = $defaultLocale;
    }

    public function build ()
    {
        
        /**
         *
         * @var DataGrid $dataGrid
         */
        $dataGrid = $this->factory->createDataGrid('page_management');
        $dataGrid->setCaption(
            $this->translator->trans('grid.page_management.title',  array(), 'NeutronPageBundle')
        )
            ->setAutoWidth(true)
            ->setColNames(array(
                'category_id',
                $this->translator->trans('grid.page_management.title',  array(), 'NeutronPageBundle'),
                $this->translator->trans('grid.page_management.slug',  array(), 'NeutronPageBundle'),
                $this->translator->trans('grid.page_management.category',  array(), 'NeutronPageBundle'),
                $this->translator->trans('grid.page_management.displayed',  array(), 'NeutronPageBundle'),
                $this->translator->trans('grid.page_management.enabled',  array(), 'NeutronPageBundle'),

            ))
            ->setColModel(array(
                array(
                    'name' => 'c.id', 'index' => 'c.id', 'hidden' => true,
                ), 
                array(
                    'name' => 'p.title', 'index' => 'p.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.slug', 'index' => 'c.slug', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.title', 'index' => 'c.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                ), 
                    
                array(
                    'name' => 'c.displayed', 'index' => 'c.displayed', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true,
                    'formatter' => 'checkbox',  'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(1 => 'enabled', 0 => 'disabled'))
                ), 
                        
                array(
                    'name' => 'c.enabled', 'index' => 'c.enabled',  'width' => 40, 
                    'align' => 'left',  'sortable' => true, 
                    'formatter' => 'checkbox',  'search' => true, 'stype' => 'select',
                    'searchoptions' => array('value' => array(1 => 'enabled', 0 => 'disabled'))
                ),

            ))
            ->setQueryBuilder($this->getQb())
            ->setSortName('p.title')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_page.update', array('id' => '{c.id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_page.delete', array('id' => '{c.id}'), true))
            ->setQueryHints(array(
                Query::HINT_CUSTOM_OUTPUT_WALKER 
                    => 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker',
                \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE 
                    => $this->session->get('frontend_language', $this->defaultLocale),
            ))

            ->setFetchJoinCollection(false)
        ;


        
        return $dataGrid;
    }

    private function getQb ()
    {
        $conn = $this->em->getConnection();
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id, c.id as category_id, p.title, c.slug, c.title as category, c.enabled, c.displayed')
            ->from($this->pageClass, 'p')
            ->innerJoin('p.category', 'c')
            ->groupBy('p.id')
        ;
        
        return $qb;
    }

}