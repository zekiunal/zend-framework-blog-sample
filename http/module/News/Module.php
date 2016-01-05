<?php
namespace News;

use News\Model\News;
use News\Model\NewsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     News
 * @name        Module
 * @version     0.1
 * @created     2015/12/18 13:33
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
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

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'News\Model\NewsTable' =>  function($sm) {
                    $tableGateway = $sm->get('NewsTableGateway');
                    $user_table_gateway = $sm->get('UserTableGateway');
                    $table = new NewsTable($tableGateway, $user_table_gateway);
                    return $table;
                },
                'NewsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $user_table_gateway = $sm->get('UserTableGateway');
                    $resultSetPrototype = new ResultSet();
                    $relations['user'] = $user_table_gateway;
                    $resultSetPrototype->setArrayObjectPrototype(new News($relations));
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },
                'UserTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \News\Model\User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                }
            ),
        );
    }
}
