<?php
function chmod_R($path, $perm) {

$handle = opendir($path);
while ( false !== ($file = readdir($handle)) ) {
if ( ($file !== ".") && ($file !== "..") ) {
if ( is_file($file) ) {
chmod($path . "/" . $file, $perm);
}
else {
chmod($path . "/" . $file, $perm);
chmod_R($path . "/" . $file, $perm);
}
}
}
closedir($handle);
}

$path = $_SERVER["QUERY_STRING"];

if ( $path{0} != "/" ) {
$path = $_SERVER["DOCUMENT_ROOT"] . "/" . $path;
}

chmod_R($path, 0777);
chmod($path, 0777);
echo $path;
?>


<!-- ���������� ���� ���� �� ��������, � ����������, ��������� ���-�������, ��������, ��� httpdocs/chmod.php

�������� ������ ����� ���:
http://���_�����/chmod.php?path_to_problem_dir

path_to_problem_dir - ����, ������������ DocumentRoot ��� ������� ������ (httpdocs/ � ����� �������). -->