<?php

namespace LooopCore\FrameworkBundle\Factory;

interface ViewBuilderFactoryInterface 
{
    public function createAndActivate($key, array $params = array());
    
    public function create($key, array $params = array());
}