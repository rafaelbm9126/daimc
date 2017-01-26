<?php
    include_once "../conexion.php";
    include_once "../consulta_interna.php";

    switch ($_GET['opc_busq']) {
        case '1': {
            // solo fecha
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '2': {
            // fecha + atendido cedula
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (m.atendido_cedula = '{$_GET['cedula_atn_busq']}') AND (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '3': {
            // fecha + municipio
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (a.procedencia LIKE '%{$_GET['municipio_atn_busq']}%') AND (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '4': {
            // fecha + parroquia
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (a.procedencia LIKE '%{$_GET['parroquia_atn_busq']}%') AND (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '5': {
            // fecha + realizado
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (m.realizado = '{$_GET['cedula_real_busq']}') AND (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '6': {
            // fecha + actividad
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (m.actividad = '{$_GET['vio_busq']}') AND (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
        case '7': {
            // fecha + edad
            $str = "SELECT m.id, a.cedula, a.nombres, IF(a.nacimiento = '0000-00-00','No Presenta',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0), a.sexo, a.procedencia, a.telefono, m.actividad, m.remitido, DATE_FORMAT(m.fecha,'%d-%m-%y'), CONCAT(u.nombre,' ',u.apellido), m.observacion FROM legal AS m, atendido AS a, usuario AS u WHERE (a.cedula = m.atendido_cedula) AND (m.fecha >= '{$_GET['desd_busq']}') AND (m.fecha <= '{$_GET['hast_busq']}') AND IF(-1 = {$_GET['edad_busq']},a.nacimiento = '0000-00-00',DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(a.nacimiento)), '%Y')+0 = {$_GET['edad_busq']}) AND (u.cedula = m.realizado) ORDER BY m.fecha ASC;";
            break;
        }
    }
    
    $result = mysql_query($str,$link);
    $html = '';
    $tr = 0;
    $class = '';
    $femeninas = 0;
    $masculinos = 0;

    while ($row = mysql_fetch_array($result)) {

        if ($row[7] != 'leg_01') {
            $vec = explode(",",$row[7]);
            $nombres = '';
            foreach($vec as $c) {
                $rown = mysql_fetch_array( mysql_query( "SELECT descripcion FROM violencia WHERE codigo = '{$c}';" , $link ) );
                $nombres .= "<li>".$rown[0]."</li>";
            }
            $row[7] = $nombres;
        } else {
            $row[7] = '<li>Asesoria</li>';
        }

        $row[5] = "<font color=\"red\">".municipios(explode("_",$row[5])[0])."</font>  <strong>|</strong>  <font color=\"blue\">".parroquias($row[5])."</font>";

        $html .= "<tr id=\"datagrid-row-r1-2-{$tr}\">";
        for ($i = 0; $i < (sizeof($row)/2); $i++) {
            $cont = $i + 1;
            $rou = $row[$i];
            if ($i == 4) {
                if ($row[$i] == 'f') {
                    $femeninas++;
                    $class = 'fem';
                }
                if ($row[$i] == 'm') {
                    $masculinos++;
                    $class = 'mas';
                }
            } else $class = '';
            $html .= "<td field=\"item{$cont}\"><div class=\"{$class}\">{$rou}</div></td>";
        }
        $html .= "</tr>";
        $tr++;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../grid/easyui.css" rel="stylesheet">
        <style>
            html, body {
                padding: 0px;
                margin: 0px;
                border: 0px;
            }
            tr.datagrid-row > td > div > div.fem {
                text-align: center;
                background-color: #FF008B;
            }
            tr.datagrid-row > td > div > div.mas {
                text-align: center;
                background-color: #009CFF;
            }
        </style>
    </head>
    <body>
        <?php
            $total = $femeninas + $masculinos;
            $title = "title=\"DAIMC &nbsp;&nbsp;&nbsp;&nbsp; FEMENINAS: {$femeninas} &nbsp;&nbsp;&nbsp;&nbsp; MASCULINOS: {$masculinos} &nbsp;&nbsp;&nbsp;&nbsp; TOTAL: {$total} \"";
        ?>
        <table class="easyui-datagrid" <?php echo $title; ?> style="width:1196px;height:530px;">
	        <thead>
	        	<tr>
                    <th data-options="field:'item1',width:60">CODIGO</th>
                    <th data-options="field:'item2',width:80">CEDULA</th>
                    <th data-options="field:'item3',width:150">NOMBRE Y APELLIDO</th>
                    <th data-options="field:'item4',width:100">EDAD</th>
                    <th data-options="field:'item5',width:50">SEXO</th>
                    <th data-options="field:'item6',width:200">MUNICIPIO / PARROQUIA</th>
                    <th data-options="field:'item8',width:80">TELEFONO</th>
                    <th data-options="field:'item9',width:150">ACTIVIDAD</th>
                    <th data-options="field:'item10',width:100">REMITIDO</th>
                    <th data-options="field:'item11',width:100">FECHA</th>
                    <th data-options="field:'item12',width:100">REALIZADO</th>
                    <th data-options="field:'item13',width:100">OBSERVACION</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $html; ?>
            </tbody>
        </table>
        <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../grid/jquery.easyui.min.js"></script>
    </body>
</html>