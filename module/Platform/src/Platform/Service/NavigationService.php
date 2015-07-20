<?php

namespace Platform\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

use Platform\Entity\Page;
use Platform\Entity\PageHasWidget;

use Platform\Entity\Repository\MenuitemRepo;
use Platform\Entity\Repository\PageRepo;
use Platform\Entity\Repository\PageHasWidgetRepo;
use Platform\Entity\Widget;

final class NavigationService implements ServiceLocatorAwareInterface
{
	
    use ServiceLocatorAwareTrait;
    
    /**
     * Class constructor
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {

    	$this->serviceLocator = $serviceLocator;
    }
    
    /**
     * Loading all menu entries available
     * @return array
     */
    public function getMenus()
    {
    	$menus = $this->getMenuItemRepo()->findAllMenu();
    	return $menus; 
    }
    
    /**
     * Loading all pages available, with information about the widgets per page.
     * In case of specified $pageCode param, the search will be for the single page
     * @param string $pageCode Forced page code
     * @return array
     */
    public function getPages($pageCode=null)
    {
    	if (!is_null($pageCode)) {
    		$pages = $this->getPageRepo()->findByCode($pageCode);
    	} else {
    		$pages = $this->getPageRepo()->findAllPages();
    	}
    	
    	if (!is_array($pages) || count($pages) == 0) {
    		return array();
    	}
    	
    	$result = array();
    	foreach ($pages as $page) {
    		/* @var $page \Platform\Entity\Page */
    		$tmpArr = array(
    			"code" => $page->getCode(),
    			"template" => $page->getTemplate(),
    			"structure" => $page->getStructure(),
    		);
    		
    		$widgetsForPage = $this->getPageHasWidgetRepo()->findByPage($page);
    		
    		if (is_array($widgetsForPage) && count($widgetsForPage) > 0) {
    			$subwidgets = array();
				foreach ($widgetsForPage as $wfp) {
					/* @var $wfp \Platform\Entity\PageHasWidget */
					$subwidgets[] = array(
						'code' => $wfp->getWidget()->getCode(),
						'width' => $wfp->getWidgetsize()->getName(),
						'position' => $wfp->getPosition()
					);
				}
				$tmpArr['widgets'] = $subwidgets;
    		}
    		
    		$result[$page->getCode()] = $tmpArr; 
    	}
    	return $result;
    }
    
    /**
     * Loading all available widgets, with information about context, structure and eventual others
     * overloads/extensions from the customer/brand association.
     * In case of specified $widgetCode param, the search will be for the single widget
     * @param string $widgetCode Forced widget code
     * @return array
     */
    public function getWidgets($widgetCode=null)
    {
    	if (!is_null($widgetCode)) {
    		$widgets = $this->getWidgetRepo()->findByCode($widgetCode);
    	} else {
    		$widgets = $this->getWidgetRepo()->findAllWidgets();
    	}
    	
    	if (!is_array($widgets) || count($widgets) == 0) {
    		return array();
    	}
    	
    	$result = array();
    	
    	foreach ($widgets as $widget) {
    		/* @var $widget \Platform\Entity\Widget */
    		$tmpArr = array(
    			"code" => $widget->getCode(),
     			"type" => $widget->getWidgettype()->getName(),
    			"structure" => $widget->getStructure(),
    			"context" => $widget->getContext(),
    			"graphic" => $widget->getGraphic()	
    		);
    		
    		$result[$widget->getCode()] = $tmpArr;
    	}
    	
    	return $result;
    }
    
    /**
     * @return MenuitemRepo
     */
    private function getMenuItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Platform\Entity\Menuitem');
    }
    
    /**
     * @return PageRepo
     */
    private function getPageRepo()
    {
    	return $this->getEntityManager()->getRepository('Platform\Entity\Page');
    }
    
    /**
     * @return PageHasWidgetRepo
     */
    private function getPageHasWidgetRepo()
    {
    	return $this->getEntityManager()->getRepository('Platform\Entity\PageHasWidget');
    }
    
    /**
     * @return Widget
     */
    private function getWidgetRepo()
    {
    	return $this->getEntityManager()->getRepository('Platform\Entity\Widget');	
    }
    
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
}