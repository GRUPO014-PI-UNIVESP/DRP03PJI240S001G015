<?php
$data1 = intval(date('m'));
echo $data1 . '<br>';
$codMes = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
$mes    = array('', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', );
echo $mes[$data1]; echo '<br>';
echo $codMes[$data1];

