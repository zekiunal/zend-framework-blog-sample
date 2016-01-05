<?php
namespace Account;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @name        module .config.php
 * @version     0.1
 * @created     2015/12/18 12:56
 */
return array(
    'router' => array(
        'routes' => array(

            'account'        => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/account[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Account\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'verify' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/account/verify',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Account\Controller',
                        'controller'    => 'Verify',
                        'action'        => 'index',
                    ),
                ),
            ),
            'confirm' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/confirm[/:token]',
                    'constraints' => array(
                        'token' => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Account\Controller',
                        'controller'    => 'Verify',
                        'action'        => 'confirm',
                    ),
                ),
            ),

        ),
    ),

    'controllers'  => array(
        'invokables' => array(
            'Account\Controller\Index'  => Controller\IndexController::class,
            'Account\Controller\Verify' => Controller\VerifyController::class
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'account' => __DIR__ . '/../view',
        ),
    ),
);