<?php
namespace News;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @name        module .config.php
 * @version     0.1
 * @created     2015/12/18 13:33
 */
return array(
    'router' => array(
        'routes' => array(
            'news' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/news[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'News\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'controllers'  => array(
        'invokables' => array(
            'News\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'news' => __DIR__ . '/../view',
        ), 'strategies'       => array(
            'ViewFeedStrategy',
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
            'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
            'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
            'zfcuser_user_service'              => 'ZfcUser\Service\User',
        ),
        'factories' => array(
            'zfcuser_module_options'                        => 'ZfcUser\Factory\ModuleOptionsFactory',
            'zfcuser_auth_service'                          => 'ZfcUser\Factory\AuthenticationServiceFactory',
            'ZfcUser\Authentication\Adapter\AdapterChain'   => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',
            'zfcuser_login_form'                            => 'ZfcUser\Factory\Form\LoginFormFactory',
            'zfcuser_register_form'                         => 'ZfcUser\Factory\Form\RegisterFormFactory',
            'zfcuser_change_password_form'                  => 'ZfcUser\Factory\Form\ChangePasswordFormFactory',
            'zfcuser_change_email_form'                     => 'ZfcUser\Factory\Form\ChangeEmailFormFactory',
            'zfcuser_user_mapper'                           => 'ZfcUser\Factory\UserMapperFactory',
            'zfcuser_user_hydrator'                         => 'ZfcUser\Factory\Mapper\UserHydratorFactory',
        ),
        'aliases' => array(
            'zfcuser_register_form_hydrator' => 'zfcuser_user_hydrator',
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    )
);