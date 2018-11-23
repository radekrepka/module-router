<?php

namespace RadekRepka\ModuleRouter;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory {
	use Nette\StaticClass;

	//TODO actions

	/**
	 * @param $modules
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter($modules) {
		$router = new RouteList;
		foreach ($modules as $module) {
			if ($module->hasChildren()) {
				$moduleList = new RouteList($module->getModule());
				foreach ($module->getChildren() as $child) {
					$moduleList[] = new Route(
						'[<locale=cs cs|en>/]' . $module->getUrl() . '/' . $child->getUrl() . '/<action>[/<id>]', [
						'presenter' => [
							Route::VALUE => $child->getModule(),
							Route::FILTER_TABLE => [
								//$child->getUrl() => $child->getModule()
							],
						],
						'action' => [
							Route::VALUE => 'default',
							Route::FILTER_TABLE => [

							],
						],
						'id' => null
					]);
				}
				$router[] = $moduleList;
			}
			else {
				$router[] = new Route('[<locale=cs cs|en>/]' . $module->getUrl() . '/<action>[/<id>]',  $module->getModule() . ':default');
			}
		}
		return $router;
	}
}