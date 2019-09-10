<?php
// защита от прямого доступа
defined('_JEXEC') or die('Restricted access');

		$thri_d = JRequest::getVar('thri_df');
		$photoshop = JRequest::getVar('photoshopf');
		$corel = JRequest::getVar('corelf');
		$auto_cad = JRequest::getVar('auto_cadf');
		$web_disign = JRequest::getVar('web_disignf');
		$starGroup = JRequest::getVar('starGroupf');

       // создаем массив для генерации SELECT списка звездность
		$starGroupArr = array (
			array('value' => -1, 'text' => 'Не сортировать по звездам' ),
			array('value' => 0, 'text' => 'Сортировать по звездам' ),
			array('value' => 1, 'text' => 'Все с 1 звездой и больше'),
			array('value' => 2, 'text' => 'Все с 2 звездами и больше'),
			array('value' => 3, 'text' => 'Все с 3 звездами и больше'),
			array('value' => 4, 'text' => 'Все с 4 звездами и больше'),
			array('value' => 5, 'text' => 'Все с 5 звездами'),

		);

?>
 <h2>Список дизайнеров</h2>

<form class="fiofilter" action="index.php?option=com_portfolios" method="post" name="adminForm" id="adminForm">
<strong> Фильтр:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>  Количество звезд &nbsp;<?php
echo JHTML::_('select.genericlist', $starGroupArr, 'starGroupf', 'class="inputbox"', 'value', 'text', $starGroup);?>
&nbsp; Навыки:  <?php
					($thri_d)? $checked1 ="checked" : $checked1 ="";
					($photoshop)? $checked2 ="checked" : $checked2 ="";
					($corel)? $checked3 ="checked" : $checked3 ="";
					($auto_cad)? $checked4 ="checked" : $checked4 ="";
					($web_disign)? $checked5 ="checked" : $checked5 ="";

					?>
					<input type="checkbox" name="thri_df" value="1" <?php echo $checked1; ?> >3D Max&nbsp;&nbsp;
					<input type="checkbox" name="photoshopf" value="1" <?php echo $checked2; ?> >Photo Shop&nbsp;&nbsp;
					<input type="checkbox" name="corelf" value="1" <?php echo $checked3; ?> >Corel&nbsp;&nbsp;
					<input type="checkbox" name="auto_cadf" value="1" <?php echo $checked4; ?> >Auto Cad&nbsp;&nbsp;
					<input type="checkbox" name="web_disignf" value="1" <?php echo $checked5; ?> >Web - дизайн
&nbsp;&nbsp;
<button onclick="this.form.submit();">Применить</button>       <br />

	</form>
<form action="index.php?option=com_portfolios" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<tr>
			<!--//<th></th>//-->
			<th><?php echo JText::_('NAME'); ?></th>
			<th><?php echo JText::_('OSNOV_NAVIK'); ?></th>
			<th><?php echo JText::_('NAVIK'); ?></th>
			<th><?php echo JText::_('TELEFON')."<br>".JText::_('EMAIL'); ?></th>

		</tr>
		<?php if ($this->rows):
			foreach ($this->rows as $i => $row):
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = 'index.php?option=com_portfolios&task=edit&thri_df='.$thri_d.
					'&photoshopf='.$photoshop.'&corelf='.$corel.'&auto_cadf='.$auto_cad.
					'&web_disignf='.$web_disign.
					'&starGroupf='.$starGroup.
					'&cid[]=' . $row->id;
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><a href="<?php echo $link; ?>"><?php echo $row->fio; ?></a><br />
					 <div style='width : 120px;float:left;'>
                     <font size="6" color="#ffcc00" ><?php
                    for ($i = 0 ; $i < $row->star_reyting;$i++)
					{
						echo  "*";
						}
					?></font><font size="6" color="708090" ><?php
					  for ($i = 0 ; $i < 5 - $row->star_reyting;$i++)
					{
						echo  "*";
						}  ?></font></div>
					</td>
					<td><?php

					$navyck_ARR =explode("\n",$row->privut_notes);
					$navyck =  $navyck_ARR[0]."<br />".$navyck_ARR[1]."<br />".$navyck_ARR[2]."<br />".$navyck_ARR[3];
					echo $navyck; ?></td>
					<?php
					$navik = '';
					if ($row->thri_d) $navik .="3D Max<br>";
					if ($row->photoshop) $navik .="Photo Shop<br>";
					if ($row->corel) $navik .="Corel<br>";
					if ($row->auto_cad) $navik .="Auto Cad<br>";
					if ($row->web_disign) $navik .="Web - дизайн";
                    if ($navik == '') $navik="нет";
					?>
					<td><?php echo $navik; ?></td>
					<?php
					$contact = $row->telefon."<br>".$row->email; ?>
					<td><?php echo $contact; ?></td>
				</tr>

			<?php
			endforeach;?>
			<tr>
				<td colspan="4">
					<?php
					// echo $this->pagination->getPagesLinks();
					$htmlLinks = "&amp;thri_df=".$thri_d."&amp;photoshopf=".$photoshop."&amp;corelf=".$corel."&amp;auto_cadf=".$auto_cad."&amp;web_disignf=".$web_disign."&amp;starGroupf=".$starGroup;
					$PageLinks = str_replace("option=com_portfolios", "option=com_portfolios".$htmlLinks,  $this->pagination->getListFooter());
					$PageLinks = str_replace("Display Num" , "Количество записей" ,  $PageLinks);

					echo  $PageLinks;
					 ?>
				</td>
			<tr>
		<?php else: ?>
			<tr>
				<td colspan="4"><?php echo JText::_('NO DATA'); ?></td>
			<tr>
		<?php endif; ?>
	</table>
	<?php $msg=print_R($row,true);
	echo '<!-- ###################'.$msg."###############-->";?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />

	<input type="hidden" name="thri_df" value="<?php echo $thri_d; ?>" />
	<input type="hidden" name="photoshopf" value="<?php echo $photoshop; ?>" />
	<input type="hidden" name="corelf" value="<?php echo $corel; ?>" />
	<input type="hidden" name="auto_cadf" value="<?php echo $auto_cad; ?>" />
	<input type="hidden" name="web_disignf" value="<?php echo $web_disign; ?>" />
	<input type="hidden" name="starGroupf" value="<?php echo $starGroup; ?>" />

</form>