<?php

namespace RadekRepka\ModuleRouter;

use Kdyby\Translation\Translator;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\ArrayList;
use Tracy\Debugger;

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
	 * @param User|null $user
	 * @return array|ArrayHash
	 */
	public function getModules(User $user = null) {
		if($this->modules == null || $user) {
			$this->modules = $this->buildModules($this->neonArray, $user);
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
	 * @param User|null $user
	 * @param string|null $parent
	 * @return ArrayHash
	 */
	protected function buildModules(array $modulesArray, User $user = null, string $parent = null) {
		$routes = new ArrayHash();
		foreach($modulesArray as $key => $moduleDat) {
			if (is_array($moduleDat) && array_key_exists('modules', $moduleDat)) {
				$moduleName = $this->translator->translate('modules.' . $key . '._name');
				$module = new Module($key, $moduleName, $parent);
				$module->setChildren($this->buildModules($moduleDat['modules'], $user, $key));
			}
			else {
				if ($parent) {
					$moduleName = $this->translator->translate('modules.' . $parent . '.' . $key);
				}
				else {
					$moduleName = $this->translator->translate('modules.' . $key);
				}

				$module = new Module($key, $moduleName, $parent);

				if (is_array($moduleDat)){
					if (array_key_exists('icon', $moduleDat)) {
						$module->setIcon($moduleDat['icon']);
					}

					if (array_key_exists('allow', $moduleDat) && is_array($moduleDat['allow'])) {
						$module->setAllows($moduleDat['allow']);
					}
				}
			}

			if ($user &&
				is_array($moduleDat) &&
				array_key_exists('allow', $moduleDat) &&
				is_array($moduleDat['allow'])
			) {
				if ($module->isAllowed($user)) {
					$routes[$key] = $module;
				}
			}
			else {
				$routes[$key] = $module;
			}
//			$routes[$key] = $module;
		}
		return $routes;
	}
}
