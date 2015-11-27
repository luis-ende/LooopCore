<?php

namespace LooopCore\FrameworkBundle\Builder\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Controller\BaseController;
use LooopCore\FrameworkBundle\Builder\AbstractViewBuilder;

/**
 * Base abstract implementation of a tree builder. A tree builder is a
 * helper class used by a controller to organize and coordinate the elements
 * of a single tree view (a view of data in a tree form). Based on the DHTMLX Tree Component
 * (see http://dhtmlx.com/docs/products/dhtmlxTree/)
 */
abstract class AbstractTreeBuilder extends AbstractViewBuilder
{
    public function __construct(BaseController $controller, array $options = [])
    {
        parent::__construct($controller, $options);

        if ($this->options['auto_initialize_tree_wrapper']) {
            $this->setTree($this->getTreeWrapper());
        }
    }

    /**
     * @var TreeWrapper
     */
    protected  $dataTree;

    public function getTree()
    {
        return $this->dataTree;
    }

    public function setTree($dataTree)
    {
        $this->dataTree = $dataTree;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(['auto_initialize_tree_wrapper' => true]);
    }

    public function getViewParameters($prefix = "form", $formVariableName = "form")
    {
        return ['tree' => $this->dataTree];
    }

    public function initializeTree()
    {
        if (is_null($this->dataTree) || !($this->dataTree instanceof TreeWrapper)) {
            throw new \Exception('Data Tree not assigned or invalid type. Data Tree View cannot be initialized.');
        }

        $this->loadTree();
    }

    abstract protected function loadTree();

    protected function getTreeWrapper()
    {
        return new TreeWrapper(0, 'tree_root');
    }
}