<?php

namespace RadekRepka\ModuleRouter;

use Nette;

class Module {
	use Nette\SmartObject;

	/** @var string */
	protected $module;

	/** @var string */
	protected $name;

	/** @var string */
	protected $parent;

	/** @var string */
	protected $url = null;

	/** @var string */
	protected $icon;

	/** @var Nette\Utils\ArrayHash */
	protected $children;

	/**
	 * Module constructor.
	 * @param string $module
	 * @param string $name
	 * @param string|null $parent
	 */
	public function __construct(string $module, string $name, string $parent = null) {
		$this->module = $module;
		$this->name = $name;
		$this->parent = $parent;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon(string $icon) {
		$this->icon = $icon;
	}

	/**
	 * @param Nette\Utils\ArrayHash $children
	 */
	public function setChildren(Nette\Utils\ArrayHash $children) {
		$this->children = $children;
	}

	/**
	 * @return string
	 */
	public function getModule(): string {
		return $this->module;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return string
	 */
	public function getFullModule(): string {
		if ($this->getParent())
			return $this->getParent() . ':' . $this->getModule();
		return $this->getModule();
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		if ($this->url === null) {
			$this->url = Nette\Utils\Strings::webalize($this->name);
		}
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @return bool
	 */
	public function hasChildren() {
		return $this->children && $this->children->count() > 0;
	}
}