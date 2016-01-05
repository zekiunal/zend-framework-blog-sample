<?php
/**
 * If you need an environment-specific system or application configuration,
 * there is an example in the documentation
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-system-configuration
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-application-configuration
 *
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @name        application .config.php
 * @version     0.1
 * @created     2015/12/18 12:44
 */
return array(
    /**
     * This should be an array of module namespaces used in the application.
     */
    'modules' => array(
        //3rd Party
        'ZendDeveloperTools',
        'DOMPDFModule',
        'ZfcBase',
        'ZfcUser',

        // Portal Modules
        'Welcome',
        'Account',
        'News',
    ),

    /**
     * These are various options for the listeners attached to the ModuleManager
     */
    'module_listener_options' => array(
        /**
         * This should be an array of paths in which modules reside.
         * If a string key is provided, the listener will consider that a module
         * namespace, the value of that key the specific path to that module's Module class.
         */
        'module_paths' => array(
            '../module',
            '../vendor',
        ),

        /**
         * An array of paths from which to glob configuration files after modules are loaded. These effectively override
         * configuration provided by modules themselves. Paths may use GLOB_BRACE notation.
         */
        'config_glob_paths' => array(
            '../config/autoload/{{,*.}global,{,*.}local}.php',
        ),
    ),
);


