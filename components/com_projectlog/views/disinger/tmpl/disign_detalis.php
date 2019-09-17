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
        .end {background: #92ff86;}
        .on {background: #1dacff;}
        .set {background: #92ff86;}
        .ower {background: #ffbdac;}
        .akt {background: #feffa4;}
        .rid{background:#ffff00; }
        }
    </style>

<div id="container">
    <table id="name" cellspacing="0" cellpadding="0">
        <thead>
            <th> <sub>Проекты</sub>&nbsp;\&nbsp;<sup>Дата</sup></th>
        </thead>
        <?php
        foreach ($this->dDitalis as $data){
            echo '<tr><div>
                    <table>
                        <tr><td class="right">'.$data->title.'</td></tr>
                        <tr><td class="right rid">'.$data->release_id.'</td></tr>
                        </table></div>
                   </tr>';
        }
        ?>

<!--        <tr>-->
<!--            <td class="bottom">&nbsp;</td>-->
<!--        </tr>-->
    </table>
    <div id="wrap">
        <table id="data" cellspacing="0" cellpadding="0">
            <thead>
            <?php
            for ($dd=1; $dd<=$number;$dd++){
                $dnum = date("w",strtotime($dd.date('.m.Y', strtotime($this->startDate))));
                echo '<th>'.$dd.'<br>'.$days[$dnum].'</th>';
            }
            ?>
                <th>Календарик</th><!--Календарик-->
            </thead>

            <?php
            $k = 0;

            foreach ($this->dDitalis as $data){
                echo '<tr class="row".$k>';
                for ($dd=1; $dd<=$number;$dd++){
                    if(isset($this->dDitalis[$dd])) {

                    }
                        echo '<td class="right">&nbsp;</td>';
                }
                echo '<td>Календарик</td>';
                echo '</tr>';
            }
            $k=1 - $k;
            ?>
<!--            <tr class="row1">-->
<!--                <td>1</td>-->
<!--                <td class="on">3</td> В работе -->
<!--                <td class="on">5</td> В работе -->
<!--                <td class="on">1</td><!- В работе -->
<!--                <td class="set">1</td> План сдачи -->
<!--                <td class="ower">3</td> Просрочка -->
<!--                <td class="akt">1</td> Акт -->
<!--                <td>5</td>Последний день месяца-->
<!--                <td>7</td>Календарик-->
<!--            </tr>-->
<!--            <tr class="end"> Выполнено -->
<!--                <td>2</td>-->
<!--                <td>4</td>-->
<!--                <td>6</td>-->
<!--                <td>1</td>-->
<!--                <td>23</td>-->
<!--                <td>4</td>-->
<!--                <td>1</td>-->
<!--                <td>6</td>-->
<!--                <td>6</td>-->
<!--            </tr>-->

            <!--<tr>
                <td class="bottom" colspan="9">&nbsp;</td>
            </tr>-->
        </table>
    </div>
</div>
</body>
</html>
