<?php
// ������ �� ������� �������
defined('_JEXEC') or die('Restricted access');

class zepp_client_contact extends JTable
{

	public $id = null;// @var int(11) - ��������� ����

	public $id_client = null; // @var int(11) - ���� ����������� � zepp_client

	public $town = "������� "; // @var varchar(30) - �����

	public $post = "�������� ";//@var date - ���������

	public $telefon = null; // @var int(12)  - �������

	public $email = null; // @var varchar(60) - ����������� �����

	public $fio = null; // @var varchar(60) -���

	public $birthday = null; // @var date - ���� ��������


	/**
	 * �����������
	 *
	 * @param object $db ������ ���� ������ JDatabase
	 */
	function __construct(&$db)
	{
		parent::__construct('#__zepp_client_contact', 'id', $db);
	}
}