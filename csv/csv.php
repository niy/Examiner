<?PHP/*
ini_set("memory_limit","1024M");
ini_set('auto_detect_line_endings', true);
ini_set('max_execution_time', 1000);

$db = mysql_connect('localhost', 'root', 'toor');
mysql_select_db('examiner', $db);

$final = fopen("users.csv", "r");

while (($row = fgetcsv($final)) !== false) {
    $uname=$row[0];
    $ulname=$row[1];
    $fname=$row[2];
    $uid=$row[3];
    $password=$row[4];
    $email=$row[5];
    $sqlstring ="INSERT INTO users (FName, LName, fatherName, userid, password, email) VALUES ('$uname', '$ulname', '$fname', '$uid', '$password', '$email')";
    $result = mysql_query($sqlstring, $db);
}
*/
/****************************************************************
ini_set("memory_limit","1024M");
ini_set('auto_detect_line_endings', true);
$father = fopen("father.csv", "r"); //only name (one column)
$f1 = fopen("1.csv", "r");
$f2 = fopen("2.csv", "r");
$f3 = fopen("3.csv", "r");
$f4 = fopen("4.csv", "r");
$i=0;
$j=0;
$fath[]=array();
while (($fd = fgetcsv($father))!== FALSE ) {
    $fath[$j]=$fd[0];
    $j++;
}
while (($data1 = fgetcsv($f1))!== FALSE ) {
    $fname[$i]=$data1[0];
    $lname[$i]=$data1[1];
    $lname2[$i]=$data1[2];
    $uname[$i]=$data1[3];
    $pass[$i]=$data1[4];
    $email[$i]=$data1[5];
    $i++;
}
while (($data2 = fgetcsv($f2))!== FALSE ) {
    $fname[$i]=$data2[0];
    $lname[$i]=$data2[1];
    $lname2[$i]=$data2[2];
    $uname[$i]=$data2[3];
    $pass[$i]=$data2[4];
    $email[$i]=$data2[5];
    $i++;
}
while (($data3 = fgetcsv($f2))!== FALSE ) {
    $fname[$i]=$data3[0];
    $lname[$i]=$data3[1];
    $lname2[$i]=$data3[2];
    $uname[$i]=$data3[3];
    $pass[$i]=$data3[4];
    $email[$i]=$data3[5];
    $i++;
}
while (($data4 = fgetcsv($f2))!== FALSE ) {
    $fname[$i]=$data4[0];
    $lname[$i]=$data4[1];
    $lname2[$i]=$data4[2];
    $uname[$i]=$data4[3];
    $pass[$i]=$data4[4];
    $email[$i]=$data4[5];
    $i++;
}
for ($c=0;$c<$i;$c++) {
    $dest[$c][0] = $fname[$c];
    $dest[$c][1] = $lname[$c];
    $dest[$c][2] = $fath[rand(0,count($fath))];
    $dest[$c][3] = $uname[$c];
    $dest[$c][4] = $pass[$c];
    $dest[$c][5] = $email[$c];
}

$fp = fopen('users.csv', 'w');
foreach ($dest as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);
**************************************************************/

/*
echo "<table>\n";
echo "<td><b>1</b></td><td><b>2</b></td><td><b>3</b></td><td><b>4</b></td><td><b>5</b></td><td><b>6</b></td>
        <td><b>7</b></td><td><b>8</b></td><td><b>9</b></td><td><b>10</b></td><td><b>11</b></td><td><b>12</b></td>";
foreach ($dest as $index => $data){
    echo ("<tr>");
    $num = count($data);
    for ($c=0; $c < $num; $c++)
    {
        echo "<td>".$data[$c]."</td>\n";
    }
    echo ("<tr>");
}
var_dump($dest);*/


/**************************
fclose($father);
fclose($f1);
fclose($f2);
fclose($f3);
fclose($f4);
**************************/
?>