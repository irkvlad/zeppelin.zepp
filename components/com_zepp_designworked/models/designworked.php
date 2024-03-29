<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//echo "class designworkedsModelDesignworked extends JModel111111111 url_path==". JPATH_BASE . "<br>";
jimport('joomla.application.component.model');
//require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS .'irkvladhelper.php');
//require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS .'html.helper.php');

class designworkedsModelDesignworked extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__zepp_designworked '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->greeting = null;
		}
		return $this->_data;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getDataId($id)
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__zepp_designworked '.
				'  WHERE id = '.$id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->greeting = null;
		}
		return $this->_data;
	}

	/**
	 * @param $p
	 * @return Table|false;
	 */
	function storeToSQL($p)
	{
		$post = $p;
		$row =& $this->getTable('zepp_designworked');
//echo 'storeToSQL($p)<br>';
//print_R($p,false);
//echo '<br>'.dump($p).'<br>p->id='.$p['id'].'<br>p->cid='.$p['cid'];
//echo '<br>'.dump($row);

		// Присваеваем значения
		if (!$row->bind($post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Проверяем на корректность
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Сохраняем в базе
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row;
	}

	/**
	 * Сохраняем запись
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($p)
	{
//echo "designworkedsModelDesignworked store()<br>";

//echo 'Удаляем файлы'.$p['del'][0].'<br>';
//dump($p);
		if($p['del']){
			// Удаляем файлы
			$message=designworkedHTML::delFiles($p,".");
//echo 'Файлы удалены'.$p['del'][0].'<br>';	
//dump($message);		
//echo "p['path']=message['path']: $p['path']=$message['path'];<br>";
//echo '$p[\'path\']=$message[\'path\']:'."$p['path']=$message['path'];<br>";
			$p['path']=$message['path'];
			$p['privu']=$message['privu'];

//echo 'Удалили файлы'.$p['del'][0].'<br>';
//dump($p);	
		}
//!!!!!!!!!!!!!!!!!! Константы !!!!!!!!!!!!!!
		$extensions_IMAG = array('jpg', 'jpeg', 'png', 'gif');
		$upload_dir = './images/designworked';  // папка для загрузки (создать на сервере)
		$extensions_DOC = array('cdr', 'pdf', 'psd', 'doc', 'xls', 'docx', 'xlsx');
		$max_file_size=10;
		$data['error']='';
		$data['info']='';

		if ($row = $this->storeToSQL($p)){


			if (!empty($_POST['task'])) // если кнопка "button"  нажата
			{
				for ($i = 0, $length = count($_FILES['file']['name']); $i < $length; $i++) {
					$file_extension = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
					$message='';
					if (in_array(strtolower($file_extension), $extensions_IMAG)) {
							$message = irkvladHTML::uploadHandle($max_file_size, $extensions_IMAG, $upload_dir, $row->id, $i);
//echo 'message= <br>'.print_R($message, false).'<br>';
							$privu = str_replace('.jpg', '_privu.jpg', $message['pathfile']);
							//Создать привьюшки
							irkvladHTML::imgResize($message['pathfile'], $privu, 170, 80);
//echo 'imgResize  <br>';
							$row->path .= '.'.$message['pathfile'] . ';';
							$row->privu .= '.'.$privu . ';';
							$row->store();

							// Выводим сообщение
						if ($message['error']){
							$data['error'] .= $message['error'].'<br>' ;
						}else {
							$data['info'] .= $message['info'].'<br>' ;
							$data['pathfile' . $i] =  '.'.$message['pathfile'];
						}

					}else if( in_array(strtolower($file_extension), $extensions_DOC)){
						$message = irkvladHTML::uploadHandle($max_file_size, $extensions_DOC, $upload_dir, $row->id, $i);
						$row->path .= '.'.$message['pathfile'] . ';';
						$privu=explode(".",$message['pathfile']);
						$row->privu .= '.'.$privu[count($privu)-1] . '.jpg;'; // Делаю ярлык
						$row->store();

						$data['privu'.$i]= $privu;
						// Выводим сообщение
						if ($message['error']){
							$data['error'] .= $message['error'].'<br>' ;
						}else {
							$data['info'] .= $message['info'].'<br>' ;
							$data['pathfile' . $i] =  '.'.$message['pathfile'];
						}
					}else {
						$data['error'] .= 'У файла'.$_FILES['file']['name'][$i].' недопустимое расширение<br>';
					}
				}
			}
		}

$data['row']=$row;
		return $data;
	}

	/**
	 *  Удаляет файлы
	 * @param $row
	 * @param $cid
	 * @return bool
	 */

	function delFiles($row, $cid)
	{
//echo '<br>delFiles() cid='.$cid;
		$row->load($cid);
		$fales = explode(";",$row->path);
		$f=true;
//dump($row);
		for ($i=0 ; $i < count($fales); $i++){
//echo '<br>Look fales:'.$fales[$i];
			if (file_exists(substr($fales[$i], 1))) {
//echo '<br>delFile:'.$fales[$i];
				if (!unlink(substr($fales[$i], 1))) {
//echo '<br>Err delFile:'.$fales[$i];
				$f=false;}
			}
		}
		$privus = explode(";",$row->privu);
		for ($i=0 ; $i < count($privus); $i++){
			if (file_exists(substr($privus[$i], 1))) {
				if (!unlink(substr($privus[$i], 1))) $f= false;
				$privus[$i] = '';
			}
		}

		$row->path = implode(";", $fales);
		$row->privu = implode(";", $privus);
		$row->store();

		return $f;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
//echo '<br>delete()';
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable('zepp_designworked');

		if (count($cids)) {
			foreach ($cids as $cid) {
				if( ! $this->delFiles($row, $cid)) return false;

				if (!$row->delete($cid)) {
					$this->setError($row->getErrorMsg());
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Возвращает записи с работами определенного дизайнера в определенной категории
	 * @param $dis - id дизайнера
	 * @param $cat - id категории
	 * @return mixed
	 */
	function getItem(){
		$dis	= JRequest::getVar( 'dis' );
		$cat	= JRequest::getVar( 'cat' );

		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__zepp_designworked '.
			'  WHERE userid = '.$dis.' AND catid = '.$cat;
		$db->setQuery( $query );
		$item = $db->loadObjectList();

		return $item;
	}



}