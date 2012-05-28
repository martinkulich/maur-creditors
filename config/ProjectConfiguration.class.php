<?php

require_once  dirname(__FILE__).'/host.config.php';
require_once dirname(__FILE__) . './../lib/vendor/symfony/1.4.9/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{

    public function setup()
    {
        $this->enablePlugins(array(
            'sfPropelPlugin',
            'securityPlugin',
            'reservationPlugin',
            ));
    }

}
