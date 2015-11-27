<?php

namespace LooopCore\FrameworkBundle\Builder\Tree;

use LooopCore\FrameworkBundle\Commons\LooopNode;

class TreeWrapper extends LooopNode
{
    public $id;
    public $title;
    public $data = [];

    public function __construct($id = 0, $title, array $data = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->data = $data;

        parent::__construct($id, $this->options);
    }

    public function getTreeDataAsJson()
    {
        $treeItems = ['id' => 0, 'item' => $this->treeWrapperToArray($this->getChildren())];
        $jsonResponse = \json_encode($treeItems);

        return $jsonResponse;
    }

    private function treeWrapperToArray($nodeChildren)
    {
        $treeItems = [];
        /** @var TreeWrapper $treeNode */
        foreach ($nodeChildren as $treeNode) {
            $userData = [];
            foreach($treeNode->data as $key => $value) {
                $userData[] = ['name' => $key, 'content' => $value];
            }

            if ($treeNode->hasChildren()) {
                $treeItems[] = ['id' => $treeNode->id, 'text' => $treeNode->title, 'userdata' => $userData,
                    'item' => $this->treeWrapperToArray($treeNode->getChildren())];
            } else {
                $treeItems[] = ['id' => $treeNode->id, 'text' => $treeNode->title, 'userdata' => $userData];
            }
        }

        return $treeItems;
    }
}