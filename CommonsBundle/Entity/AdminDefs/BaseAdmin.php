<?php
namespace LooopCore\CommonsBundle\Entity\AdminDefs;

use RuntimeException;
use Sonata\AdminBundle\Admin\Admin;

class BaseAdmin extends Admin {
    /**
     * Überschreiben der Parent-Klasse
     * Ziel: Jede Klasse hat garantiert eine eigene Route, so dass
     * mehrere Views zur gleichen Entity ermöglicht werden.
     *
     * @throws RuntimeException
     *
     * @return string the baseRouteName used to generate the routing information
     */
    public function getBaseRouteName()
    {
        if (!$this->baseRouteName) {
            preg_match(self::CLASS_REGEX, get_class($this), $matches);

            if (!$matches) {
                throw new RuntimeException(sprintf('Cannot automatically determine base route name, please define a default `baseRouteName` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                $this->baseRouteName = sprintf('%s_%s',
                    $this->getParent()->getBaseRouteName(),
                    $this->urlize($matches[5])
                );
            } else {

                $this->baseRouteName = sprintf('admin_%s_%s_%s',
                    $this->urlize($matches[1]),
                    $this->urlize($matches[3]),
                    $this->urlize($matches[5])
                );
            }
        }

        return $this->baseRouteName;
    }
    
    
    /**
     * Überschreiben der Parent-Klasse
     * Ziel: Jede Klasse hat garantiert eine eigene Route, so dass
     * mehrere Views zur gleichen Entity ermöglicht werden.
     *
     * @throws RuntimeException
     *
     * @return string the baseRoutePattern used to generate the routing information
     */
    public function getBaseRoutePattern()
    {
        if (!$this->baseRoutePattern) {
            preg_match(self::CLASS_REGEX, get_class($this), $matches);

            if (!$matches) {
                throw new RuntimeException(sprintf('Please define a default `baseRoutePattern` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                $this->baseRoutePattern = sprintf('%s/{id}/%s',
                    $this->getParent()->getBaseRoutePattern(),
                    $this->urlize($matches[5], '-')
                );
            } else {

                $this->baseRoutePattern = sprintf('/%s/%s/%s',
                    $this->urlize($matches[1], '-'),
                    $this->urlize($matches[3], '-'),
                    $this->urlize($matches[5], '-')
                );
            }
        }

        return $this->baseRoutePattern;
    }
}
