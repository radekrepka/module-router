# Module router

- [Description](#description)
- [Installation](#installation)
- [Usage](#usage)

## Description
Simple tool which generates menu, links and titles of pages.

## Installation

```sh
$ composer require radekrepka/module-router
```

## Usage
Configuration is in neon files.

```neon
extensions: 
	moduleRouter: RadekRepka\ModuleRouter\DI\ModuleRouterExtension
```
Then you can add your modules and pages in menu.
You can set icon of item in menu. It can be anything (url to image file, fa icon...).
#### config.neon

```neon
moduleRouter:
	modules:
		Presenter1:
		Presenter2:
			
		Module1: #(Admin, Costumer...)
			modules:
				Presenter1:
					icon: home
				Presenter2:
					icon: ....
		Module2:
			modules:
				Presenter1:
					icon: ....
				Presenter2:
					icon: ....			
```
#### Translation file (modules.cs_CZ.neon)
You must create translation file called modules.

```neon
Presenter1: Some page
Presenter2: Some page 2
Module1:
	_name: Module 1
	Presenter1: Homepage
	Presenter2: Settings
Module2:
	_name: Module 2
	Presenter1: ...
```

#### RouterFactory:

```php
<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use RadekRepka\ModuleRouter\ModuleManager;

class RouterFactory {
	use Nette\StaticClass;

	/**
	 * @param ModuleManager $manager
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter(ModuleManager $manager) {
		$router = $manager->getRouter();
		$router[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}
```

#### BasePresenter:

```php
	/** @var ModuleManager @inject */
	public $moduleManager;
	
	public function beforeRender() {
		$modules = $this->moduleManager->getModules();
		//Or from module
		$modules = $this->moduleManager->getModules()->offsetGet('Module1')->getChildren();
		$this->template->modules = $modules;
		$this->template->currentModule = $modules->offsetGet($this->getPresenterName());
	}
	
	public function getPresenterName() {
		return explode(':', $this->getName())[1];
	}
```
#### Template (@layout.latte for example)

```latte
<ul>
    {foreach $modules as $module}
        {var $active = $presenter->getName() == $module->getFullModule()}
        <li{if $active} class="active"{/if}>
            <a n:href="$module->getModule() . ':'">
                <img n:if="$module->getIcon()" src="{$basePath}/img/{$module->getIcon()}">
                <span>{$module->getName()}</span>
            </a>
        </li>
    {/foreach}
</ul>
    
...
    
<h1>{$currentModule->getName()}</h1>

