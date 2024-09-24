<?php

date_default_timezone_set('America/Sao_Paulo');

$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(!empty($data['data1'])){
$data1 = new DateTime($data['data1']);

echo $data1->format('d.m.Y');

$data2 = data1->modify('+2 days');

echo $data2->format('Y/m/d');
}
?>
<form action="" method="POST">
   <input type="date" id="data1" name="data1" onchange="this.form.submit()">
</form>
