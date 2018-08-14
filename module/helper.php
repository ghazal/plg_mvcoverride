<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\Registry\Registry;

/**
 * Module helper class
 *
 * @package     Joomla.Legacy
 * @subpackage  Module
 * @since       11.1
 */
abstract class JModuleHelper extends JModuleHelperLibraryDefault
{
    /**
     * Render the module.
     *
     * @param   object  $module   A module object.
     * @param   array   $attribs  An array of attributes for the module (probably from the XML).
     *
     * @return  string  The HTML content of the module output.
     *
     * @since   11.1
     */
    public static function renderModule($module, $attribs = array())
    {
        static $chrome;

        // Check that $module is a valid module object
        if (!is_object($module) || !isset($module->module) || !isset($module->params))
        {
            if (JDEBUG)
            {
                \JLog::addLogger(array('text_file' => 'jmodulehelper.log.php'), \JLog::ALL, array('modulehelper'));
                \JLog::add('ModuleHelper::renderModule($module) expects a module object', \JLog::DEBUG, 'modulehelper');
        }

            return;
        }

        if (JDEBUG)
        {
            \JProfiler::getInstance('Application')->mark('beforeRenderModule ' . $module->module . ' (' . $module->title . ')');
        }

        $app = \JFactory::getApplication();

        // Record the scope.
        $scope = $app->scope;

        // Set scope to component name
        $app->scope = $module->module;

        // Get module parameters
        $params = new JRegistry;
        $params->loadString($module->params);

        // Get the template
        $template = $app->getTemplate();

        // Get module path
        $module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
        $path = JPATH_THEMES . '/' . $template . '/code/' . $module->module . '/' . $module->module . '.php';

        if (!file_exists($path))
        {
            $path = JPATH_BASE . '/code/' . $module->module . '/' . $module->module . '.php';
            if (!file_exists($path))
            {
                $path = JPATH_BASE . '/modules/' . $module->module . '/' . $module->module . '.php';
            }
        }

        // Load the module
        if (file_exists($path))
        {
            $lang = \JFactory::getLanguage();

            $coreLanguageDirectory      = JPATH_BASE;
            $extensionLanguageDirectory = dirname($path);

            $langPaths = $lang->getPaths();

            // Only load the module's language file if it hasn't been already
            if (!$langPaths || (!isset($langPaths[$coreLanguageDirectory]) && !isset($langPaths[$extensionLanguageDirectory])))
            {
            // 1.5 or Core then 1.6 3PD
                $lang->load($module->module, $coreLanguageDirectory, null, false, true) ||
                    $lang->load($module->module, $extensionLanguageDirectory, null, false, true);
            }

            $content = '';
            ob_start();
            include $path;
            $module->content = ob_get_contents() . $content;
            ob_end_clean();
        }

        // Load the module chrome functions
        if (!$chrome)
        {
            $chrome = array();
        }

        include_once JPATH_THEMES . '/system/html/modules.php';
        $chromePath = JPATH_THEMES . '/' . $template . '/html/modules.php';

        if (!isset($chrome[$chromePath]))
        {
            if (file_exists($chromePath))
            {
                include_once $chromePath;
            }

            $chrome[$chromePath] = true;
        }

        // Check if the current module has a style param to override template module style
        $paramsChromeStyle = $params->get('style');

        if ($paramsChromeStyle)
        {
            $attribs['style'] = preg_replace('/^(system|' . $template . ')\-/i', '', $paramsChromeStyle);
        }

        // Make sure a style is set
        if (!isset($attribs['style']))
        {
            $attribs['style'] = 'none';
        }

        // Dynamically add outline style
        if ($app->input->getBool('tp') && ComponentHelper::getParams('com_templates')->get('template_positions_display'))
        {
            $attribs['style'] .= ' outline';
        }

        // If the $module is nulled it will return an empty content, otherwise it will render the module normally.
        $app->triggerEvent('onRenderModule', array(&$module, &$attribs));

        if ($module === null || !isset($module->content))
        {
            return '';
        }

        foreach (explode(' ', $attribs['style']) as $style)
        {
            $chromeMethod = 'modChrome_' . $style;

            // Apply chrome and render module
            if (function_exists($chromeMethod))
            {
                $module->style = $attribs['style'];

                ob_start();
                $chromeMethod($module, $params, $attribs);
                $module->content = ob_get_contents();
                ob_end_clean();
            }
        }

        // Revert the scope
        $app->scope = $scope;

        $app->triggerEvent('onAfterRenderModule', array(&$module, &$attribs));

        if (JDEBUG)
        {
            \JProfiler::getInstance('Application')->mark('afterRenderModule ' . $module->module . ' (' . $module->title . ')');
        }

        return $module->content;
    }
}
