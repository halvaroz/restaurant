<?php

define('ROOT_PATH', __DIR__);

define('CFG_PATH', realpath(ROOT_PATH.'/application/config'));


define('WWW_PATH', realpath(ROOT_PATH.'/application/www'));


require_once 'library/Configuration.class.php';
require_once 'library/Database.class.php';
require_once 'library/FlashBag.class.php';
require_once 'library/Form.class.php';
require_once 'library/FrontController.class.php';
require_once 'library/MicroKernel.class.php';
require_once 'library/Http.class.php';
require_once 'library/InterceptingFilter.interface.php';


$microKernel = new MicroKernel();
$microKernel->bootstrap()->run(new FrontController());