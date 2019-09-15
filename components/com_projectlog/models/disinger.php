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
    var $_startDate = null;
    var $_startDateLast = null;
    var $_endDate = null;
    var $_endDateLast = null;
    var $_catId = null;
    var $_disigner = null;
    var $_manager = null;


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

        $this->_startDate = date('Y-m-01');
        $startDate = JRequest::getString('startDate', date('Y-01-01'));
        if (strtotime($startDate) >= strtotime($this->_startDate))
            $this->_startDateLast = date('Y-m-01', strtotime('-1 month', strtotime($this->_startDate)));
        else
            $this->_startDateLast = date('Y-m-d', strtotime($startDate));

        $this->_endDate = date('Y-m-d', strtotime(JRequest::getString('endDate', date("Y-m-d"))));
        $this->_endDateLast = date('Y-m-t', strtotime('- 1 days', strtotime($this->_startDate)));

        $this->_catId = JRequest::getInt('catid', '10');

        $this->_disigner = JRequest::getString('disigner', '');
        $this->_manager = JRequest::getString('manager', '');

    }

    /**
     * Возвращает количество работ дизайнера в дитализации по менеджерам. На текущий месяц.
     * @return object|null набор данных ввиде двух мерного массива.
     *  Data([порядковый номер из перебора менеджеров] -> объект, [Порядковый номер из перебора дизайнеров] -> объект)
     */
    function getData()
    {
        // if (empty($this->_dataLast)) {
        $this->_data = $this->gedDesignWorked($this->getManagersId(), $this->getDisignersId(), $this->_startDateLast, $this->_endDate, $this->_catId);
        // }

        return $this->_data;

    }

    /**
     * Возвращает количество работ дизайнера в дитализации по менеджерам. С начальной даты.
     * @return object|null набор данных ввиде двух мерного массива.
     *  Data([порядковый номер из перебора менеджеров] -> объект, [Порядковый номер из перебора дизайнеров] -> объект)
     */
    function getDataLast()
    {
        // if (empty($this->_dataLast)) {
        $this->_dataLast = $this->gedDesignWorked($this->getManagersId(), $this->getDisignersId(), $this->_startDateLast, $this->_endDateLast, $this->_catId);
        // }

        return $this->_dataLast;
    }

    /**
     * 
     * @return bool|object
     */
    function getDataDetalis()
    {
        $dataDetalis = false;
        if ($this->_disigner) {
            $dataDetalis = $this->gedDesignWorked($this->_manager, $this->_disigner, $this->_startDate, $this->_endDate, $this->_catId);

        }
        return $dataDetalis;
    } //NEDD:

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
    public function gedDesignWorked($managersId, $designersId, $startDate, $endDate, $catid = 10)
    {
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
                    . " AND category <> " . $catid  // Категория проектов
                    . " AND contract_from > DATE_FORMAT('" . $startDate . " 00:00:00','%Y-%m-%d %H:%i:%S')"
                    . " AND contract_from <= DATE_FORMAT('" . $endDate . " 00:00:00','%Y-%m-%d %H:%i:%S')"
                    . " AND release_date > '0000-00-00' ";

                if ($managersId) $query .= " AND manager=" . $ManagerId;


                $db->setQuery($query);

                $data[$i]->disigner[$t]->id = $designersId[$t]->user_id;
                $data[$i]->disigner[$t]->name = $designersId[$t]->name;
                $data[$i]->disigner[$t]->count = $db->loadResult();
                $data[$i]->disigner[$t]->countTotall = $this->getDisignerAllWorck($designersId[$t]->user_id, $endDate);
            }
        }
        return $data;
    }

    /**
     * Функция получает объект со всем списком работ сданных активными дизайнерами
     */
    function getDisignerAllWorck($designersId, $endDate)
    {
        $db = JFactory::getDBO();
        $data = null;
        $query = " SELECT "
            // . " chief " // Подсчитать количество записей
            . " COUNT(*) as count "
            . " FROM "
            . " jos_projectlog_projects "
            . " WHERE "
            . " chief = " . $designersId
            . " AND contract_to <= DATE('" . $endDate . "') " // Дата когда проект был перемещен в категорию 7 - "Отдать в производство"
            . " AND category = 10 "   // Сданные проекты
        ;

        $db->setQuery($query);
        $data = $db->loadResult();
        return $data;

    }

    /**
     * Выборка списка не завершенных работ по ID-менеджера и дизанера. предшедствавшие указаной даты. (Выходящие на период работы)
     * @return object  id, release_id, shot_title, release_date
     */
    function getTotallOnDate()
    {
        return $totalOnDate = null;
    }

    /**
     * Функция записи планов сдачи работ дизайнеров по проекту менеджеру
     */
    function setDisignWorckPlan()
    {
        return false;
    }



}
