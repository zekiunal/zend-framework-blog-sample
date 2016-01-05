<?php
namespace Welcome;


use Zend\EventManager\Event;
use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use News\Model\News;
use News\Model\NewsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     Welcome
 * @name        Module
 * @version     0.1
 * @created     2015/12/18 12:55
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(
            'route',
            function (MvcEvent $e) {
                $app = $e->getApplication();
                $routeMatch = $e->getRouteMatch();
                $sm = $app->getServiceManager();
                $auth = $sm->get('zfcuser_auth_service');
                $route = $routeMatch->getMatchedRouteName();
                if (!$auth->hasIdentity() && $route != 'zfcuser/login' && $route != 'zfcuser/register' && $route != 'home' && $route != 'news' && $route == 'verify' && $route != 'confirm') {
                    $response = $e->getResponse();
                    $response->getHeaders()->addHeaderLine(
                        'Location',
                        $e->getRouter()->assemble(
                            array(),
                            array('name' => 'zfcuser/login')
                        )
                    );
                    $response->setStatusCode(302);
                    return $response;
                }
            },
            -100
        );

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'News\Model\NewsTable' => function ($sm) {
                    $tableGateway = $sm->get('NewsTableGateway');
                    $user_table_gateway = $sm->get('UserTableGateway');
                    $table = new NewsTable($tableGateway, $user_table_gateway);
                    return $table;
                },
                'NewsTableGateway'     => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $user_table_gateway = $sm->get('UserTableGateway');
                    $resultSetPrototype = new ResultSet();
                    $relations['user'] = $user_table_gateway;
                    $resultSetPrototype->setArrayObjectPrototype(new News($relations));
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },
                'UserTableGateway'     => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \News\Model\User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                }
            ),
        );
    }
}
