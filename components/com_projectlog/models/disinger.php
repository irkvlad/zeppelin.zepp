<?php
/**
 * @version       1.0.0 2019-08
 * @package       Joomla
 * @subpackage    Project Log
 * @copyright (C) IRkvlad
 * @link          
 * @license       GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');
jimport('joomla.application.component.model');

function chmod_R($path, $perm)
{
	$handle = opendir($path);
	while (false !== ($file = readdir($handle)))
	{
		if (($file !== ".") && ($file !== ".."))
		{
			if (is_file($path . "/" . $file))
			{
				chmod($path . "/" . $file, $perm);
			}
			else
			{
				chmod($path . "/" . $file, $perm);
				chmod_R($path . "/" . $file, $perm);
			}
		}
	}

	closedir($handle);
}


class ProjectlogModelDisinger extends JModel
{
    var $_id = null;
    var $_data = null;
    var $_dataLast = null;
    var $_startDate= null;
    var $_startDateLast= null;
    var $_endDate= null;
    var $_endDateLast= null;
    var $_catId= null;
    var $_disigner= null;
    var $_manager= null;


    function __construct()
    {
        parent::__construct();
        global $option;

        $mainframe =& JFactory::getApplication();
        $this->init();
       // $this->getData(); // для отладки
    }

    function init()
    {
        $this->_id = JRequest::getInt('id', '0');

        $this->_startDateLast = date_create(     JRequest::getString('startDate',date('Y-m-01', strtotime('-1 month')))      );
        $this->_startDate =date('Y-m-01');

        if (date_format($this->_startDateLast, 'Y-m-d')) {
            $this->_startDateLast = date_format($this->_startDateLast,'Y-m-d');
            //$this->_startDate = date_format($this->_startDate, 'Y-m-d');
        }
        else {
            $this->_startDateLast = date_create(    date('Y-m-01', strtotime('-1 month'))   );
            $this->_startDateLast = date_format(   $this->_startDateLast,'Y-m-d'       );
            $this->_startDate = date('Y-m-01');
        }

        $this->_endDate = date_create(      JRequest::getString('endDate', date("Y-m-d"))       );
        $this->_endDateLast = date_create(      JRequest::getString('endDate', date("Y-m-t", strtotime("-1 month")))       );

        if (date_format($this->_endDate, 'Y-m-d')) {
            $this->_endDateLast = date_format($this->_endDateLast,'Y-m-d');
            $this->_endDate = date_format($this->_endDate, 'Y-m-d');
        }
        else {
            $this->_endDateLast = date_create(    date("Y-m-t", strtotime("-1 month"))   );
            $this->_endDateLast = date_format(   $this->_endDateLast,'Y-m-d'       );
            $this->_endDate = date('Y-m-d');
        }

        $this->_catId = JRequest::getInt('catid',  '6');

        $this->_disigner= JRequest::getString('disigner', '');
        $this->_manager= JRequest::getString('manager', '');
        $data =$this->getDisignerAllWorck();

        // NEDD: Выборка по деталям
    }

    /**
     * Возвращает набор данных ввиде двух мерного массива. Data(порядковый номер из перебора менеджеров, Порядковый номер из перебора дизайнеров)
     * @return object|null
     */
    function getData()
    {
       // if (empty($this->_dataLast)) {
            $this->_dataLast = $this->gedDesignWorked($this->getManagersId(), $this->getDisignersId(), $this->_startDate, $this->_endDate, $this->_catId);
       // }

        return $this->_dataLast;

    }

    function getDataLast()
    {
       // if (empty($this->_dataLast)) {
            $this->_dataLast = $this->gedDesignWorked($this->getManagersId(), $this->getDisignersId(), $this->_startDateLast, $this->_endDateLast, $this->_catId);
       // }

        return $this->_dataLast;
    }

    function getDataDetalis()
    {
        $dataDetalis = false;
        if($this->_disigner) {
            $dataDetalis = $this->gedDesignWorked($this->_manager, $this->_disigner, $this->_startDate, $this->_endDate, $this->_catId);

        }
        return $dataDetalis;
    }

     /**
     * Функция получает список опубликованных менеджеров
     * @return obect  объект содержит свойства int $user_id и string $name - Имя менеджера
     */
    function getManagersId()
    {
        // Получаем объект базы данных
        $db = JFactory::getDBO(); // Формируем запрос (OR c.catid=3)
        $query = " SELECT "
            . " id "
            . " FROM "
            . " jos_categories "
            . " WHERE "
            . " title LIKE 'Менеджеры' "
            . " AND published = 1 ";

        $db->setQuery($query);
        $catid = $db->loadResult();

        $query = " SELECT "
            . " user_id, "
            . " name  "
            . " FROM "
            . " jos_contact_details "
            . " WHERE "
            . " published=1 "
            . " AND company = 1" // Признак работы в цепелине
            . " AND catid = "
            . $catid
            . " ORDER BY name";

        $db->setQuery($query);
        $managersID = $db->loadObjectList();
        return $managersID;

    }


    /**
     * Функция возвращает список опубликованных дизайнеров
     * @return obect Содержит int @user_id и string name - Имя менеджера
     */
    function getDisignersId()
    {

        // Получаем объект базы данных
        $db = JFactory::getDBO(); // Формируем запрос (OR c.catid=20)
        $query = " SELECT "
            . " id "
            . " FROM "
            . " jos_categories "
            . " WHERE "
            . " title LIKE 'Дизайнер' "
            . " AND published=1 ";

        $db->setQuery($query);
        $catid = $db->loadResult();

        $query = " SELECT "
            . " user_id, "
            . " name  "
            . " FROM "
            . " jos_contact_details "
            . " WHERE "
            . " published=1 "
            . " AND company = 1"
            . " AND catid = "
            . $catid
            . " ORDER BY name";

        $db->setQuery($query);
        $designersID = $db->loadObjectList();
        return $designersID;
    }


    /**
     * Возвращает набор данных (см. getData()) по входящим параметрам
     * @param obect $managersId Массив id менеджеров для выборки
     * @param obect $designersId Массив id дизайнеров для выборки
     * @param $startDate date Начальная дата, будет премещенна на 1 число месяца
     * @param $endDate date Конечная  дата
     * @param $catid int категория проектов
     * @return object  Двух мерный массив
     */
    public function gedDesignWorked($managersId, $designersId, $startDate, $endDate, $catid = 6){
        $db = JFactory::getDBO();
        $data = null;
        for ($i = 0, $n = count($managersId); $i < $n; $i++) { // Первая сторона массива менеджеры
            $ManagerId = $managersId[$i]->user_id;
            $data[$i]->id = $managersId[$i]->user_id;
            $data[$i]->name = $managersId[$i]->name;

            for ($t = 0, $tn = count($designersId); $t < $tn; $t++) { // Вторая сторона массива дизайнеры
                $designerID = $designersId[$t]->user_id;

                $query = " SELECT "
                    . " COUNT(*) " // Подсчитать количество записей
                    . " FROM "
                    . " jos_projectlog_projects "
                    . " WHERE chief=" . $designerID
                    . " AND category = " . $catid  // Категория проектов
                    . " AND contract_from " //DATE_FORMAT('01.02.2019','%Y-%m-%d')
                    . "     BETWEEN  DATE_SUB(DATE_FORMAT('" . $startDate . " 00:00:00','%Y-%m-%d %H:%i:%S'), INTERVAL DAYOFMONTH(DATE_FORMAT('" . $startDate . " 00:00:00','%Y-%m-%d %H:%i:%S')) + 1 DAY)"
                    . "         AND DATE_FORMAT('" . $endDate . " 00:00:00','%Y-%m-%d %H:%i:%S')"
                    ;

                if ($managersId) $query .= " AND manager=" . $ManagerId;


                $db->setQuery($query);

                $data[$i]->disigner[$t]->id = $designersId[$t]->user_id;
                $data[$i]->disigner[$t]->name = $designersId[$t]->name;
                $data[$i]->disigner[$t]->count = $db->loadResult();
                getDisignerAllWorck($designersId[$t]->user_id, $endDate);//NEDD Доработать доработать доработать

                //$data[$ManagerId][$designerID] =
            }
        }
        return $data;
    }

    /**
     * Функция получает объект со всм списком работ сданных активными дизайнерами
     */

    function getDisignerAllWorck()
    {
        $db = JFactory::getDBO();
        $data = null;
        $query = " SELECT "
            . " chief " // Подсчитать количество записей
            . " , COUNT(*) as count "
            . " FROM "
            . " jos_projectlog_projects "
            . " WHERE "
            . " category = 10 "   // Сданные проекты
            . " AND chief <> 0 " //DATE_FORMAT('01.02.2019','%Y-%m-%d')
            . " GROUP BY chief "
        ;

        $db->setQuery($query);
        $data = $db->loadAssocList();
        return $data;

    }

}
