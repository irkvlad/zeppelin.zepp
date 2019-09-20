<?php defined('_JEXEC') or die('Restricted access');

$m = date("m");
$y = date("Y");
$number = cal_days_in_month(CAL_GREGORIAN, $m, $y);

$days = array(
    'Вс', 'Пн', 'Вт', 'Ср','Чт', 'Пт', 'Сб'
);
$monthes = array(
    1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
    5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
    9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
);

$doc_path  = 'media/com_projectlog/docs/';

$link_set = "/index.php?option=com_projectlog&view=disinger&layout=disign_detalis&disigner=".$this->_models['disinger']->_disigner."&manager=".$this->_models['disinger']->_manager."&Itemid=124";

?>

<h1>Детализация работ</h1>

<span>Дизайнер <span><b><?php echo $this->disigner ?></b></span>, менеджер <span><b><?php echo $this->manager ?></b></span> </span><br>
<span>Период: <span><b><?php echo $monthes[(date('n', strtotime($this->startDate)))].' '.date('Y', strtotime($this->startDate)); ?></b></span></span><br>

<!-- ****************************  STYLE ***************************************************************-->
    <style type="text/css">
       /* body {margin:0;padding:0}
        #container {width:605px}*/
        table#name {
            width:100px;
           /* background:#ffffcc;*/
            float:left;
        }
        table#data {
            /*background:#ffffcc;*/
            width:100%;
        }
       th {max-height: 30px;min-height: 30px;height: 30px;}
       tr {max-height: 70px;min-height: 70px;height: 70px;}
        td,th {
            border: 1px solid;
        }
        td {
            text-align: center;
            padding: 5px;
        }
        #data tr:hover {background: #ccfff1  }
       td.right {text-align: right; padding-right: 5px; overflow-wrap: break-spaces; }
        #data td {width:100px;}
        #wrap{
            /*width:500px;*/
            overflow:auto;
            overflow-y:hidden;
            /*border-right:1px solid red;
            float:left;*/
        }
        .bottom {/*background:#CCCCFF*/}
        .row1 {background: #f0ebff
        }
       .rig {
           width: 100%;
           border: none;
           padding: 0;
           margin: 0;
           height: 0
       }
       .dey{background: rgba(255, 255, 255, 1);}
       .sdey{background: #a1e9ff;}
        .end {background: #92ff86;}
        .on {background: #1dacff;}
        .pset {background: #ff999c;}
        .set {background: #ffbba4;}
        .ower {background: #ffbdac;}
        .akt {background: #feffa4;}
        .ylou{background:#ffff00; }
        .the{background: #F17F1A; }
       td.proj{ background: transparent; padding: 0;}
       td.proj :hover { background: #1dacff;padding: 0;}

        div.wprev{
            float: left;
            width: 300px;
            border: 1px solid black;
            display: block;
            padding: 5px;}
        div.wpost{
            float: right;
            width: 300px;
            border: 1px solid black;
            display: block;
            padding: 5px;}
    </style>
<!-- ****************************  STYLE ***************************************************************-->

<div id="container">
    <?php
// Работы перед периодом
    if($this->totallOnDate->count > 0) :
        echo '<div class="wprev">';
        echo '<form action="index.php" method="post" name="adminForm" id="adminForm">';
        echo '<span>В прошлом периоде: <b> '.$this->totallOnDate->count.' проект(ов)</b><br><span> Посмотреть: ';
//echo JHTML::_('select.genericlist', $state, $name = 'test', $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false );
//              $state - массив с элементами списка;
//              $name - имя списка (<select name="xxx">), так же подставляется в id тэга, если не установленна переменная $idtag;
//              $attribs - атрибуты списка; Немного из html, что может пригодиться для атрибутов. disabled - Блокирует доступ и изменение элемента. multiple - Этот параметр позволяет одновременно выбирать сразу несколько элементов списка. size - Количество отображаемых строк списка.
//              $key и $text лучше оставлять как и есть, т.е. 'value' и 'text';
//              $selected - значение выбранного елемента по умолчанию;
//              $idtag - значение id для списка;
//              $translate - это переменная, которая позволяет переводить текст. Т.е. допустим если бы я описал первый элемент так $state[] = JHTML::_('select.option', $value = '1', $text= 'First'); и поставил бы $translate = true, то значение $text преобразовалось бы в JText::_( 'First' ).
        echo JHTML::_('select.genericlist', $this->project_list, 'endDate', 'size="1"'
                .'onchange="document.location=\'' . $link_set . '&amp;endDate=\' +this.options[this.selectedIndex].value " '
                , 'contract_from', 'release_id', 'Проект', '0', false);
        echo "</span></form></div>";
    endif;

// Работы после перииода
    if($this->totallPostDate->count > 0) :
        echo '<div class="wpost">';
        echo '<form action="index.php" method="post" name="adminForm" id="adminForm">';
        echo '<span>В следующем периоде: <span> '.$this->totallPostDate->count.' проект(ов)</span><br><span> Посмотреть: ';
        echo JHTML::_('select.genericlist', $this->project_Postlist, 'endDate', 'size="1"'
                .'onchange="document.location=\'' . $link_set . '&amp;endDate=\' +this.options[this.selectedIndex].value " '
                , 'contract_from', 'release_id', 'Проект', '0', false);
        echo "</span></form></div>";
    endif;
    echo '<br clear="all">';
?>


<!--Фиксированный столбец - проекты -->
    <div style="padding-top: 10px" class="tbl">
    <table  id="name" cellspacing="0" cellpadding="0">
        <thead>
            <th class="the"> <sub>Проекты</sub>&nbsp;\&nbsp;<sup>Дата</sup></th>
        </thead>
        <?php
        //Перебор проектов
        foreach ($this->dDitalis as $data){
            $link = JRoute::_( "index.php?option = com_projectlog&cat_id=0&view=project&Itemid=48&id=". $data->pid);
            echo '<tr><td class="proj hasTip" 

title="Перейти к проекту :: Создан:<b> '
                .$data->contract_from.'</b><br> План эскиза: <b>'
                .($data->contract_to_1 ? $data->contract_to_1 : "нет").'</b><br> План завершения:<b> '
                .($data->release_date ? $data->release_date : "нет").'</b><br> Статус <b>'
                .projectlogHTML::getCatName($data->category).' '
                .(strtotime($data->contract_to) > 0 ? $data->contract_to : "") .'</b><br> Акты:<b> '.($data->akt ? $data->akt : "нет").' ">

                    <a href="' . $link . '" >
                    <table class="rig">
                     
                        <tr class="rig"><td class="rig">'.$data->shot_title.'</td></tr>
                        <tr class="rig"><td class="ylou rig">'.$data->release_id.'</td></tr>
                    
                    </table>
                    </a>
                    </td>
                   </tr>';
        }
        ?>
    </table>
    <div id="wrap">
<!--Дни - календарь-->
        <table id="data" cellspacing="0" cellpadding="0">
<!--Заголовок данных-->
            <thead>
            <?php
            $color='dey'; //Цвет дня недели
            for ($dd=1; $dd<=$number;$dd++){
                //День недели
                $dnum = date("w",strtotime($dd.date('.m.Y', strtotime($this->startDate))));
                if($dnum == 0 OR $dnum== 6) {$color='ylou';}
                else  {$color='dey';}
                echo '<th class="the">'.$dd. '<br><span class="'.$color.'">' .$days[$dnum].'</span></th>';
            }
            ?>
                <th class="the">Календарик</th><!--Календарик-->
            </thead>

            <?php
 //Тело данных
            $k = 0;//Четная строка или не четная
            foreach ($this->dDitalis as $data){ //Перебор поектов
                $path = $pathT = $tunbsrc = '';//Пути до календаря

                echo '<tr class="row".$k>';

 // Пербор столбцов - дней месяца
                for ($dd=1; $dd<=$number;$dd++){
                    $c='dey';
                    $t="";
                    $dnum = date("w",strtotime($dd.date('.m.Y', strtotime($this->startDate))));//день недели

                    //Дата создания проекта - отметить class=on
                    if( isset($this->dDitalis[$dd]) AND $this->dDitalis[$dd]->pid == $data->pid ){
                        $c='on';//текущий столбец - ячейка цвет
                        $t="Создан";
                    }
                    //Выходные дни-отметить class=sdey
                    if($dnum == 0 OR $dnum == 6){
                        $c='sdey';
                        $t="";
                    }
                    //План сдачи проекта NEDD: Поправить дату
                    if(strtotime($data->release_date) == strtotime(date($dd."-m-Y",strtotime($this->startDate)))){
                        $c="end";
                        $t="Сдача проекта";
                    }
                    //План сдачи эскиза
                    if($data->contract_to_1 AND strtotime($data->contract_to_1) == strtotime(date($dd."-m-Y",strtotime($this->startDate)))){
                        $c="pset";
                        $t="Сдача эскиза";
                    }
                    //Сдан эскиз
                    if(strtotime($data->contract_to) > 0 and strtotime($data->contract_to) == strtotime(date($dd."-m-Y",strtotime($this->startDate))) ){
                        $c="set";
                        $t="Эскиз сдан";
                    }

                    echo '<td class="'.$c.'">'.$t.'</td>';
                }
                echo '<td class="proj">';//Календарик
                if (is_null($data->lid)) echo 'Календарик отсутсвует';
                else {
                    $path = $doc_path . $data->pid . DS . $data->path;//путь к календарику
                    $pathT = $doc_path . $data->lid . DS . "80x80_". $data->path;//путь к превьюшке
                    if (    file_exists($pathT) )  // превьюшки может не быть
                        $tunbsrc = '<img src='. $pathT .' width="60" height="60" alt="Логотип">' ;
                    else  $tunbsrc = '<img src='. $path .' width="60" height="60" alt="Логотип">' ;
                    echo  '<a target="_blank" title="Открыть превью" href="' . $path . '">'.$tunbsrc.'</a>' ;
                }

               echo '</td>';
               echo '</tr>';
            }
            $k=1 - $k;
            ?>
        </table>
    </div></div>
    <div class="but">
        <form action="index.php" method="post" name="adminForm" id="adminForm">
        <button style="float:left;"
                onclick="document.location='<?php echo $link_set ?>&amp;endDate=<?php echo $this->startDate ?>'"
<!--                onclick="document.location.assign('--><?php //echo $link_set . '&amp;endDate='.$this->startDate; ?>//')"
                title='Перейти'><?php echo "Предыдущий месяц" ?></button>
        <button style="float:right;"
                onclick="document.location.assign('<?php echo $link_set . '&amp;endDate='. date('Y-m-t', strtotime('+ 1 days', strtotime($this->endDate))); ?>')"
                title='Перейти'><?php echo "Следущий месяц" ?></button>
        </form>
    </div>
</div>
</body>
</html>
