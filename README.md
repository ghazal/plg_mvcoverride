plg_mvcoverride
===============

Joomla plugin to override Joomla MVC.

Plugin used (and updated for j!3) in these joomla docs :

 [How to override the component mvc from the Joomla! core - Joomla! Documentation](http://docs.joomla.org/How_to_override_the_component_mvc_from_the_Joomla!_core)


###Usage example
For a component :
>/templates/your\_template/code/com\_search/views/search/view.html.php

For component controllers, replace the line :
 >require\_once JPATH\_COMPONENT . '/controller.php'; BY  
 >require\_once JPATH\_SOURCE\_COMPONENT . '/controller.php';

For a module :
>/templates/your\_template/code/module\_name/helper.php  
>/templates/your\_template/code/module\_name/module\_name.php OR  
>/code/module\_name/module\_name.php
