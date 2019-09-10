<?php
/**
 * @version       1.5.3 2009-10-12
 * @package       Joomla
 * @subpackage    Project Log
 * @copyright (C) 2009 the Thinkery
 * @link          http://thethinkery.net
 * @license       GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');
jimport('joomla.application.component.view');

class ProjectlogViewDisinger extends JView
{
	function display($tpl = null) //
	{
		//global $option;
		JHTML::_('behavior.tooltip');
		//$settings      = &JComponentHelper::getParams('com_projectlog');

		$document      = &JFactory::getDocument();
		$this->baseurl = JURI::base();
		$document->addStyleSheet($this->baseurl . '/components/com_projectlog/assets/css/zepp_designer.css');
        $document->addScript($url='/includes/js/joomla.javascript.js', $type = "text/javascript");

        $startDate = date_create($this->_models['disinger']->_startDate);
        $this->assignRef('startDate', 	date_format($startDate,'01.m.Y')    );
        $endDate = date_create($this->_models['disinger']->_endDate);
        $this->assignRef('endDate', date_format($endDate,'d.m.Y')         );

        $startDateLast = date_create($this->_models['disinger']->_startDateLast);
        $this->assignRef('startDateLast', 	date_format($startDateLast,'01.m.Y')    );
        $endDateLast = date_create($this->_models['disinger']->_endDateLast);
        $this->assignRef('endDateLast', date_format($endDateLast,'d.m.Y')         );

        // NEDD: Выборка по деталям  работ дизайнеров
        $disignDetalis = $this->get('DataDetalis');
        $this->assignRef('disignDetalis', $disignDetalis);


		$user = JFactory::getUser();
        $this->assignRef('user', $user);

        // Текущий месяц
        $data  =  $this->get('data');
        $this->assignRef('data', $data);

        //Прошлый месяц
        $dataLast  =  $this->get('dataLast');
        $this->assignRef('dataLast', $dataLast);

        parent::display($tpl);

//		$model   = &$this->getModel();
//		$logs    = &$this->get('logs');
//		$docs    = &$this->get('docs');
//		$logo    = &$this->get('logo');
//		$akts    = &$this->get('akts');
//		$document->setTitle($project->title);
//		$doc_path = 'media/com_projectlog/docs/';
        //
		//список  бригадиров
//		$db    =& JFactory::getDBO();
//		$query = "SELECT c.id AS value, c.name AS text FROM #__contact_details AS c WHERE (c.catid=20 ) AND c.published=1";
//		$db->setQuery($query);
//		$categorylist  = $db->loadObjectList();
//		$categories    = array();
//		$categories[]  = JHTML::_('select.option', '0', "Выберите бригадира", 'value', 'text');
//		$categories    = array_merge($categories, $categorylist);
//		$brigadir_list = $categories;
//
//		$this->assignRef('logs', $logs);
//		$this->assignRef('docs', $docs);
//		$this->assignRef('akts', $akts);
//		$this->assignRef('logo', $logo);
//		$this->assignRef('doc_path', $doc_path);
//		$this->assignRef('settings', $settings);
//		$this->assignRef('brigadir_lis', $brigadir_list);

	}
}
