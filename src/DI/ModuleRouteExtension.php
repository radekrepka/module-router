<?php
namespace RadekRepka\ModuleRoute\DI;

use Nette\DI\CompilerExtension;
use RadekRepka\ModuleRoute\ModuleManager;

class ModuleRouteExtension extends CompilerExtension {

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
