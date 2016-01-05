<?php
namespace Account;

use News\Model\News;
use News\Model\NewsTable;
use News\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     Account
 * @name        Module
 * @version     0.1
 * @created     2015/12/18 13:17
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(
            'route',
            function (MvcEvent $e) {
                $app = $e->getApplication();
                $routeMatch = $e->getRouteMatch();
                $sm = $app->getServiceManager();
                $auth = $sm->get('zfcuser_auth_service');
                $route = $routeMatch->getMatchedRouteName();

                if ($auth->hasIdentity() && $route != 'verify' && $route != 'confirm' && $route != 'zfcuser/logout' && $auth->getIdentity()->getState() == 0) {
                        $response = $e->getResponse();
                        $response->getHeaders()->addHeaderLine(
                            'Location',
                            $e->getRouter()->assemble(
                                array(),
                                array('name' => 'verify')
                            )
                        );
                        $response->setStatusCode(302);
                        return $response;
                } else if(!$auth->hasIdentity() && $route == 'account') {
                    $response = $e->getResponse();
                    $response->getHeaders()->addHeaderLine(
                        'Location',
                        $e->getRouter()->assemble(
                            array(),
                            array('name' => 'verify')
                        )
                    );
                    $response->setStatusCode(302);
                    return $response;
                }
            },
            -100
        );

    }


    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
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
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'image_service' => 'Imagine\Gd\Imagine',
            ),
            'factories'  => array(
                'News\Model\NewsTable' => function ($sm) {
                    $tableGateway = $sm->get('NewsTableGateway');
                    $user_table_gateway = $sm->get('UserTableGateway');
                    $table = new NewsTable($tableGateway, $user_table_gateway);
                    return $table;
                },
                'News\Model\UserTable' => function ($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
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
