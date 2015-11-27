<?php

namespace LooopCore\FrameworkBundle\Twig;

use LooopCore\FrameworkBundle\Builder\Panel\PanelManager;

class PanelExtension extends \Twig_Extension
{    
    protected $defaultTemplate = "LooopCoreFrameworkBundle::_panel/blocks.html.twig";
    
    /**
     * @var PanelManager
     */
    protected $panelManager;
    
    /**
     * @var \Twig_Environment
     */
    protected $environment;
    
    /**
     * @var \Twig_TemplateInterface[]
     */
    protected $templates = array();       
    
    /**
     * @var string
     */
    protected $theme;
    
    public function __construct(PanelManager $panelManager)
    {        
        $this->panelManager = $panelManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'panel_twig_extension';
    }
    
    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'panels' => new \Twig_Function_Method($this, 'render', 
                                            array('is_safe' => array('html'))),
            'panel_widget' => new \Twig_Function_Method($this, 'renderPanel', 
                                            array('is_safe' => array('html'))));
    }
    
    /**
     * Render panels block
     *      
     * @param string $theme
     * 
     * @return string
     */
    public function render($theme = null)
    {        
        $this->theme = $theme;
        $this->templates = array();                
        
        return $this->renderBlock('panels', 
                           array('panels' => $this->panelManager->getPanels()));
    }
    
    /**
     * Render panel block
     * 
     * @param \LooopCore\FrameworkBundle\Builder\PanelBuilderInterface $panel
     * @param string $theme
     * 
     * @return string
     */
    public function renderPanel($panel, $theme = null)
    {
        $this->theme = $theme;
        $this->templates = array();
        $blockName = 'panel';
        
        if ($this->hasBlock($block = 'panel_'.$panel->getName())) {
            $blockName = $block;
        }           
        
        return $this->renderBlock($blockName, array('panel' => $panel));
    }
    
    /**
     * Render block
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     *
     * @throws \InvalidArgumentException If the block could not be found
     */
    protected function renderBlock($name, $parameters)
    {
        foreach ($this->getTemplates() as $template) {
            if ($template->hasBlock($name)) {
                return $template->renderBlock($name, array_merge($this->environment->getGlobals(), $parameters));
            }
        }

        throw new \InvalidArgumentException(sprintf('Block "%s" doesn\'t exist in grid template "%s".', $name, $this->theme));
    }
    
    /**
     * Has block
     *
     * @param $name string
     *
     * @return boolean
     */
    protected function hasBlock($name)
    {
        foreach ($this->getTemplates() as $template) {
            if ($template->hasBlock($name)) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Template Loader
     *
     * @return \Twig_Template[]
     *
     * @throws \Exception
     */
    protected function getTemplates()
    {
        if (empty($this->templates)) {
            if ($this->theme instanceof \Twig_Template) {
                $this->templates[] = $this->theme;
                $this->templates[] = $this->environment->loadTemplate($this->defaultTemplate);
            } elseif (is_string($this->theme)) {
                $this->templates = $this->getTemplatesFromString($this->theme);
            } elseif ($this->theme === null) {
                $this->templates = $this->getTemplatesFromString($this->defaultTemplate);
            } else {
                throw new \Exception('Unable to load template');
            }
        }

        return $this->templates;
    }

    protected function getTemplatesFromString($theme)
    {
        $this->templates = array();

        $template = $this->environment->loadTemplate($theme);
        while ($template != null) {
            $this->templates[] = $template;
            $template = $template->getParent(array());
        }

        return $this->templates;
    }
}