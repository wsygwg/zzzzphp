<?php

require 'BasicSetting.php';

$queryModel1 = "insert into employee values(0,'abcdefghk','1234312','fa',123425)";
$queryModel2 = "update employee SET emp_name='dddd',emp_contact='fsdfasdfasdfads' where emp_name = 'abcde'";
$queryModel3 = "DELETE FROM employee where emp_contact = '1234312'";
$queryModel4 = "alter table employee add money int(15) default 0";//添加表字段:数字
$queryModel5 = "alter table employee add nickname varchar(15) default 'xiaoming'";//添加表字段：字符串
$queryModel6 = "alter table employee change nickname profilename varchar(15) default 'ligang'";//修改表字段
$queryModel7 = "alter table employee drop profilename";//删除表字段
$queryModel8 = "select user_id,nickname,password from test_register order by user_id desc";//asc升序，desc降序

//如果成功，返回db connection。如果失败，返回false
function getDbLink(){
	$server = 'qdm210913498.my3w.com'; // this may be an ip address instead
	$user = 'qdm210913498';
	$pass = 'tjl2tjl2';
	$database = 'qdm210913498_db'; // name of your database
	
	$con = mysql_connect($server, $user, $pass);
	//解决乱码的方法，我们经常使用“set names utf8”
	//该句话一定要放在数据库服务器连接语句【$connection=mysql_connect($db_host,$db_user,$db_psw)】之后
	mysql_query("SET NAMES utf8");
	mysql_query("SET CHARACTER SET utf8");
	if (! $con) {
		$para = mysql_error();
		var_dump($para);
		return false;
	}
	$b = mysql_select_db($database, $con);
	if($b){
		return $con;
	}else{
		return false;
	}
}




