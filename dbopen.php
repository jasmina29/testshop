<?php

$hostName = "";
$userName = "root";
$password = "";
$databaseName = "test_shop";

$dbServer = mysql_connect($hostName, $userName, $password);
if (!$dbServer)
//if (!($db = mysql_connect($hostName, $userName, $password)))
{
	printf("������ ��� ���������� � MySQL !\n");
	exit();
}

if (!mysql_select_db($databaseName, $dbServer))
{
	printf("������ ���� ������ !");
	exit();
} 

?>