<?php
namespace RadekRepka\ModuleRouter\DI;

use Nette\DI\CompilerExtension;
use RadekRepka\ModuleRouteR\ModuleManager;

class ModuleRouterExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$this->compiler->loadDefinitions(
			$builder,
			[
				'ModuleManager' => [
					'class' => ModuleManager::class,
					'arguments' => [$this->config['modules']]
				]
			]
		);
	}
}
