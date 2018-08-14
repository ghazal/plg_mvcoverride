Hi everyone,

thank you for your interest for this plugin.

Unfortunately, I am running out of time these days, so I won't update it.

If anyone has a solution to make it better, please feel free to fork it.

Maybe, I'll come back in a few months, but right now I have a big project that will take all my energies.


plg_mvcoverride
===============

Joomla plugin to override Joomla MVC.

Plugin used (and updated for j!3) in these joomla docs :

 [How to override the component mvc from the Joomla! core - Joomla! Documentation](http://docs.joomla.org/How_to_override_the_component_mvc_from_the_Joomla!_core)

The joomla 2.5.x version is now dropped.

###Usage example
For a component :
>/templates/yourtemplate/code/mod_search/views/search/view.html.php

If your override file use constants, please replace these code constants :
JPATH_COMPONENT -> JPATH_SOURCE_COMPONENT,
JPATH_COMPONENT_SITE -> JPATH_SOURCE_COMPONENT_SITE and
JPATH_COMPONENT_ADMINISTRATOR -> JPATH_SOURCE_COMPONENT_ADMINISTRATOR

For a module :
>/templates/yourtemplate/code/module_name/module_name.php (required)
>/templates/yourtemplate/code/module_name/helper.php (optional)


###Issue
No more issue ATM.

Alex Chartier found a working solution.

If someone finds an issue, please tell us.
