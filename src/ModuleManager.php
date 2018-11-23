<?php

namespace RadekRepka\ModuleRoute;

use Kdyby\Translation\Translator;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\ArrayList;

class ModuleManager {
	use SmartObject;

	/** @var Translator */
	protected $translator;

	/** @var array  */
	protected $neonArray;

	/** @var array */
	protected $modules;

	/** @var Nette\Application\IRouter */
	protected $router;

	/**
	 * ModuleManager constructor.
	 * @param array $neonArray
	 * @param Translator $translator
	 */
	public function __construct(array $neonArray, Translator $translator) {
		$this->neonArray = $neonArray;
		$this->translator = $translator;
	}

	/**
	 * @return array|ArrayHash
	 */
	public function getModules() {
		if($this->modules == null) {
			$this->modules = $this->buildModules($this->neonArray);
		}
		return $this->modules;
	}

	/**
	 * @return Nette\Application\IRouter
	 */
	public function getRouter() {
		if ($this->router === null) {
			$this->router = RouterFactory::createRouter($this->getModules());
		}
		return $this->router;
	}

	/**
	 * @param array $modulesArray
	 * @param string|null $parent
	 * @return ArrayHash
	 */
	protected function buildModules(array $modulesArray, string $parent = null) {
		$routes = new ArrayHash();
		foreach($modulesArray as $key => $moduleDat) {
			if (is_array($moduleDat) && array_key_exists('modules', $moduleDat)) {
				$moduleName = $this->translator->translate('modules.' . $key . '._name');
				$module = new Module($key, $moduleName, $parent);
				$module->setChildren($this->buildModules($moduleDat['modules'], $key));
			}
			else {
				if ($parent) {
					$moduleName = $this->translator->translate('modules.' . $parent . '.' . $key);
				}
				else {
					$moduleName = $this->translator->translate('modules.' . $key);
				}
				$module = new Module($key, $moduleName, $parent);
				if (is_array($moduleDat) && array_key_exists('icon', $moduleDat))
					$module->setIcon($moduleDat['icon']);
			}
			$routes[$key] = $module;
		}
		return $routes;
	}
}