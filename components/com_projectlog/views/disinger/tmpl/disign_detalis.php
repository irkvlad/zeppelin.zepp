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

?>

<h1>Детализация работ</h1>

<h3>Дизайнер  <?php echo $this->disigner ?>, менеджер <?php echo $this->manager ?> </h3>
<h3>Период <?php echo $monthes[(date('n', strtotime($this->startDate)))].' '.date('Y', strtotime($this->startDate)); ?></h3>

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
       tr {max-height: 50px;min-height: 50px;height: 50px;}
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
        .set {background: #92ff86;}
        .ower {background: #ffbdac;}
        .akt {background: #feffa4;}
        .ylou{background:#ffff00; }
        .the{background: #F17F1A; }
       td.proj{ background: transparent; padding: 0;}
       td.proj :hover { background: #1dacff;padding: 0;}
    </style>

<div id="container">
    <table id="name" cellspacing="0" cellpadding="0">
        <thead>
            <th class="the"> <sub>Проекты</sub>&nbsp;\&nbsp;<sup>Дата</sup></th>
        </thead>
        <?php
        foreach ($this->dDitalis as $data){
            $link = JRoute::_( "index.php?option = com_projectlog&cat_id=0&view=project&Itemid=48&id=". $data->pid);
            //$text = '<strong style="font-size: 16px;">' . $_disigner->countTotall . '</strong>';
            //<a title="Перейти к проекту" href="' . $link . '"> ' . $text . ' </a>
            echo '<tr><td class="proj">
<a href="' . $link . '" class="hasTip" title=\'Перейти к проекту\' >
                    <table class="rig">
                     
                        <tr class="rig"><td class="rig">'.$data->title.'</td></tr>
                        <tr class="rig"><td class="ylou rig">'.$data->release_id.'</td></tr>
                    
                    </table>
</a>
                    </td>
                   </tr>';
        }
        ?>
    </table>
    <div id="wrap">
        <table id="data" cellspacing="0" cellpadding="0">
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
            $k = 0;//Четная строка или не четная
            $color='dey';//Цвет дня недели
            foreach ($this->dDitalis as $data){
                $path = $pathT = $tunbsrc = '';//Пути до календаря

                echo '<tr class="row".$k>';

                if( //Дата создания проекта раньше текущей даты
                    strtotime(date('d.m.Y',strtotime($data->contract_from) ))
                    < strtotime("01".date('.m.Y',strtotime($this->startDate)))) {
                    $color='on" '; //Цвет дня создания проекта
                }

                for ($dd=1; $dd<=$number;$dd++){
                    $c='dey';
                    $dnum = date("w",strtotime($dd.date('.m.Y', strtotime($this->startDate))));//день недели

                    if($dnum == 0 OR $dnum== 1) {$c='sdey';}
                    elseif( //Дата создания проекта, показать в календаре
                        strtotime(date('d.m.Y',strtotime($this->dDitalis[$dd]->contract_from) ))
                        == strtotime($dd.date('.m.Y',strtotime($this->startDate)))) {
                        $color='on';
                    }
                    $c=$color;//текущий столбец - ячейка цвет

                    echo '<td class="'.$c.' right">&nbsp;</td>';
                }
                echo '<td class="proj">';//Календарик
                if (is_null($data->lid)) echo 'Календарик отсутсвует';
                else {
                    $path = $doc_path . $data->pid . DS . $data->path;//путь к календарику
                    $pathT = $doc_path . $data->lid . DS . "80x80_". $data->path;//путь к превьюшке
                    if (    file_exists($pathT) )  // превьюшки может не быть
                        $tunbsrc = '<img src='. $pathT .' width="45" height="45" alt="Логотип">' ;
                    else  $tunbsrc = '<img src='. $path .' width="45" height="45" alt="Логотип">' ;
                    echo  '<a target="_blank" title="Открыть превью" href="' . $path . '">'.$tunbsrc.'</a>' ;
                }

               echo '</td>';
               echo '</tr>';
            }
            $k=1 - $k;
            ?>
        </table>
    </div>
</div>
</body>
</html>
