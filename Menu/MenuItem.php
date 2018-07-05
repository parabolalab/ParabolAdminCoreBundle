<?php

namespace Parabol\AdminCoreBundle\Menu;

class MenuItem implements MenuItemInterface {

	protected $label;
	protected $route;
	protected $routeParams;
	protected $icon;
	protected $position;
	protected $role;
	protected $parent;

	public function __construct($label, $route, $routeParams = null, $icon = null, $position = null, $parent = null, $role = null, $badgeAction = null, $badgeColor = 'primary')
	{
		$this->label = $label;
		$this->route = $route;
		$this->routeParams = $routeParams;
		$this->icon = $icon;
		$this->position = $position;
		$this->role = $role;
		$this->parent = $parent;
    $this->badgeAction = $badgeAction;
    if($badgeColor) $this->badgeColor = $badgeColor;
	}

	public function getLabel()
	{
		return $this->label;
	}
    public function getRoute()
    {
    	return $this->route;
    }
    public function getRouteParams()
    {
    	return (array)$this->routeParams;
    }
    public function getIcon()
    {
    	return $this->icon;
    }
    public function getPosition()
    {
    	return $this->position;
    }
    public function getRole()
    {
    	return $this->role;
    }
    public function getParent()
    {
    	return $this->parent;
    }
    public function getBadgeAction()
    {
        return $this->badgeAction;
    }
    public function getBadgeColor()
    {
        return $this->badgeColor;
    }
}