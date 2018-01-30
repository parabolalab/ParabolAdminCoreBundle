<?php

namespace Parabol\AdminCoreBundle\Menu;

interface MenuItemInterface {

	public function getLabel();
    public function getRoute();
    public function getRouteParams();
    public function getIcon();
    public function getPosition();
    public function getRole();
    public function getParent();
    public function getBadgeAction();
}