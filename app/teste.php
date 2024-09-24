<?php

date_default_timezone_set('America/Sao_Paulo');

?>

<form action="" method="POST">
   <input type="date" id="data1" name="data1" onchange="this.form.submit()">
</form>
<?php
$ok = filter_input_array(INPUT_POST, FILTER_DEFAULT);
echo $ok['data1']. '<br>';
$hoje = time(); echo $hoje . '<br>';
$data1 = strtotime($ok['data1']); echo $data1 . '<br>';

$diff_in_days = floor(($data1 - $hoje) / (60 * 60 * 24));
echo $diff_in_days . ' days';