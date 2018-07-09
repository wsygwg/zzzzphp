<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \RedBeanPHP\R as R;
require '../vendor/autoload.php';//两种方法都可以引用文件
require 'BasicSetting.php';
require 'TdbUtil.php';
require 'model/Person.php';

//貌似只有相对路径好使。绝对路径不好使...
$app = new \Slim\App;
//测试mysql各个命令

// echo("afasdfasdf");//这里一定不要添加打印，否则会报500，后面的post,get方法都无法执行，只能执行打印

//测试Php的magic method
$app->get('/magic',function (Request $request,Response $response,$args) {
// 	PHP中把以两个下划线__开头的方法称为魔术方法(Magic methods)，这些方法在PHP中充当了举足轻重的作用。 魔术方法包括:
// 	__construct()，类的构造函数
// 	__destruct()，类的析构函数
// 	__call()，在对象中调用一个不可访问方法时调用
// 	__callStatic()，用静态方式中调用一个不可访问方法时调用
// 	__get()，获得一个类的成员变量时调用
// 	__set()，设置一个类的成员变量时调用
// 	__isset()，当对不可访问属性调用isset()或empty()时调用
// 	__unset()，当对不可访问属性调用unset()时被调用。
// 	__sleep()，执行serialize()时，先会调用这个函数
// 	__wakeup()，执行unserialize()时，先会调用这个函数
// 	__toString()，类被当成字符串时的回应方法
// 	__invoke()，调用函数的方式调用一个对象时的回应方法
// 	__set_state()，调用var_export()导出类时，此静态方法会被调用。
// 	__clone()，当对象复制完成时调用
// 	__autoload()，尝试加载未定义的类
// 	__debugInfo()，打印所需调试信息
	echo "123456<br/>";
	//__construct
	$person = new Person("小丽");
	//__toString
	echo $person;
	//__invoke
	$person();
	//__isset
	$re =isset($person->sex);
	var_dump($re);
	echo("<br/>");
	$re =isset($person->name);
	var_dump($re);
	echo("<br/>");
	$re =isset($person->age);
	var_dump($re);
	echo("<br/>");
	$re =isset($person->fatherName);
	var_dump($re);
	echo("<br/>");
	
	//__sleep
	$serializeStr = serialize($person);
	var_dump($serializeStr);
	echo("<br/>");
	//__wakpup
	var_dump(unserialize($serializeStr));//匿名person在呗输出后自动执行__destruct
	echo("<br/>");
	//__call
	$person->run("teacher"); // 调用对象中不存在的方法，则自动调用了对象中的__call()方法
	$person->eat("小明", "苹果");
	//__calStatic
	$person::run("teacher"); // 调用对象中不存在的静态方法，则自动调用了对象中的__callStatic方法
	$person::eat("小明", "苹果");
	//__get
	echo "姓名：" . base64_decode($person->name) . "<br/>";  // 直接访问私有属性name，自动调用了__get()方法可以间接获取
	echo "年龄：" . $person->age . "<br/>";  // 自动调用了__get()方法，根据对象本身的情况会返回不同的值
	//__set()
	$person->name = "小红";   //赋值成功。如果没有__set()，则出错,因为name字段是private的。
	$person->age = 16; //赋值成功
	$person->age = 160; //160是一个非法值，赋值失效
	//__set_state	
	var_export($person);
	echo("<br/>");
	//__clone
	$person2 = clone $person;
	var_dump('persion1:');
	var_dump($person);
	echo '<br>';
	var_dump('persion2:');
	var_dump($person2);
	echo("<br/>");
	//__unset可以释放单个字段，也可以释放整个类	
	var_dump(isset($person->sex));
	unset($person->sex);
	var_dump(isset($person->sex));
	echo("<br/>");
	var_dump(isset($person->name));
	unset($person->name);
	var_dump(isset($person->name));
	echo("<br/>");
	var_dump(isset($person->age));
	unset($person->age);
	var_dump(isset($person->age));
	echo("<br/>");
	//__destruct
	unset($person); //销毁上面创建的对象$person
	echo("<br/>");
});

$app->get('/redbean', function (Request $request,Response $response,$args) {
	echo("redbean start<br/>");
	$server = 'qdm210913498.my3w.com'; // this may be an ip address instead
	$user = 'qdm210913498';
	$pass = 'tjl2tjl2';
	$database = 'qdm210913498_db'; // name of your database
	R::setup( 'mysql:host='.$server.';dbname='.$database,$user,$pass); //for both mysql or mariaDB
	
// 	//To delete a bean:
// 	R::trash( $book ); //for one bean
// 	R::trashAll( $books ); //for multiple beans
	//To delete all beans of a certain type:
	
// 	R::wipe( 'book' ); //burns all the books!
	
	$book = R::dispense("book");
	$book->author = "Santa Claus";
	$book->title = "Secrets of Christmas";
	$id = R::store( $book );
	echo("new id = ");
	var_dump($id);
	echo("<br/>");
	
	echo("new book = ");
	$book1 = R::load( 'book', $id ); //reloads our book
	var_dump($book1);
	echo("<br/>");
	
// 	$currentDate = R::isoDate();//这个方法有问题，网页会报错...
// 	$currentTime = R::isoTime();//这个方法有问题，网页会报错...
// 	var_dump($currentDate);
// 	echo("<br/>");
// 	var_dump($currentTime);
// 	echo("<br/>");

	R::close();
	echo("redbean stop<br/>");
	$response->getBody()->write("");
	return $response;
});

$app->get('/sql', function (Request $request,Response $response,$args) {
	echo("33333<br/>");
	$dbConnection = getDbLink();
	echo("44444<br/>");
	if(!$dbConnection){
		//获取连接失败
		$response->getBody()->write("connection failed...<br/>");
		return $response;
	}
	
	$result = mysql_query("SELECT user_id,nickname,password FROM test_register ORDER BY user_id DESC");
	var_dump($result);
	echo("<br/>");
	if(!$result){
		$error = mysql_error();
		var_dump($error);
		echo("<br/>");
	}else{
		$rows = mysql_num_rows($result);//选择的行数
		echo("row num is ");
		var_dump($rows);
		echo("<br/>");
		
		$rLengths = mysql_fetch_lengths($result);	//失败...
		echo("length is ");
		var_dump($rLengths);
		echo("<br/>");
		
		$resultFields = mysql_num_fields($result);
		echo("row fields is ");
		var_dump($resultFields);
		echo("<br/>");
		
		$l0 = mysql_field_len($result,0);
		$l1 = mysql_field_len($result,1);
		$l2 = mysql_field_len($result,2);
		echo("l0 = ".$l0." ; l1 = ".$l1." ; l2 = ".$l2);
		var_dump($l0);
		echo("<br/>");
		
		$type0 = mysql_field_type($result,0);
		$type1 = mysql_field_type($result,1);
		$type2 = mysql_field_type($result,2);
		echo("type0 = ".$type0." ; type1 = ".$type1." ; type2 = ".$type2);
		var_dump($type0);
		echo("<br/>");
		
		$flag0 = mysql_field_flags($result,0);
		var_dump($flag0);
		echo("<br/>");
	}
	
	$num = mysql_num_rows($result);//选择的行数
	for ($i = 0; $i < $num; $i++) {
		echo json_encode(mysql_fetch_assoc($result),JSON_UNESCAPED_UNICODE);
		echo($i."<br/>");
		echo json_encode(mysql_result($result,$i,"nickname"),JSON_UNESCAPED_UNICODE);//获得某一行某一字段的值
		echo($i."<br/>");
	}
	
	mysql_free_result($result);//释放result的内存
	
	$queryModel1 = "insert into employee values(0,'abcdefghk','1234312','fa',123425)";
	$s1 = mysql_query($queryModel1);
	var_dump($s1);
	if(!$s1){
		$error = mysql_error();
		var_dump($error);
		echo("<br/>");
	}else{
		$id = mysql_insert_id();//获得新插入的数据的ID
		echo("id = ");
		var_dump($id);
		echo("<br/>");
	}
	$queryModel2 = "update employee SET emp_name='dddd',emp_contact='fsdfasdfasdfads' where emp_name = 'abcde'";
	$s2 = mysql_query($queryModel2);
	var_dump($s2);
	if(!$s2){
		$error = mysql_error();
		var_dump($error);
		echo("<br/>");
	}else{
		$no = mysql_affected_rows();//获得更新的行数
		echo("no = ");
		var_dump($no);
		echo("<br/>");
	}
	$queryModel3 = "delete from employee where emp_contact = '123321'";
	$s3 = mysql_query($queryModel3);
	var_dump($s3);
	echo("<br/>");
	if(!$s3){
		$error = mysql_error();
		echo("delete failed ");
		var_dump($error);
		echo("<br/>");
	}
	
	$database = 'qdm210913498_db'; // name of your database
	$tables = mysql_list_tables($database);
	echo("tables:");
	var_dump($tables);
	echo("<br/>");
	if(!$tables){
		$error = mysql_error();
		var_dump($error);
		echo("<br/>");
	}
	echo("fields:");
	$fields = mysql_list_fields($database,"employee");
	var_dump($fields);
	echo("<br/>");
	if(!$fields){
		$error = mysql_error();
		var_dump($error);
		echo("<br/>");
	}
	
	$status = mysql_stat();//获得数据库状态
	echo("status:");
	var_dump($status);
	echo("<br/>");
	
	$b = mysql_ping();
	if(!b){
		echo("连接失效了...<br/>");
	}else{
		echo("连接正常！<br/>");
	}
	
	mysql_close();//貌似没起作用
	
	$b = mysql_ping();
	if(!b){
		echo("2连接失效了...<br/>");
	}else{
		echo("2连接正常！<br/>");
	}
	
	$response->getBody()->write("connection success!!<br/>");
	return $response;
});

/**
 * 用户名：user
 * 密码：pass
 */
$app->post('/register', function (Request $request,Response $response,$args) {
	$user = $request->getParam('user'); //checks both _GET and _POST [NOT PSR-7 Compliant]
 	$pass = $request->getParsedBody()['pass']; //checks _POST  [IS PSR-7 compliant]
// 	$pass = $request->getQueryParams()['pass']; //checks _GET [IS PSR-7 compliant]

 	echo("<br/>");
 	
 	$status = $response->getStatusCode();
 	echo("status = ");
 	var_dump($status);
 	echo("<br/>");
 	$newResponse = $response->withStatus(520);
 	$status = $newResponse->getStatusCode();
 	var_dump($status);
 	echo("<br/>");
 	
 	return $newResponse;
 	
	if(strlen($user) == 0){
		$response->getBody()->write("用户名不能为空");
		return $response;
	}
	if(strlen($pass) == 0){
		$response->getBody()->write("密码不能为空");
		return $response;
	}
	$search = "select * from test_register where nickname='$user'";//表示sql语句中的字符串外面的的单引号一定要有。不但符合sql语句的规则，而且还可以明确变量的范围，因此不用额外再加{}来表示变量
	$dbConnection = getDbLink();
	if(!$dbConnection){
		//获取连接失败
		$response->getBody()->write("数据库连接失败");
		return $response;
	}
	$result = mysql_query($search);
	$num = mysql_num_rows($result);//选择的行数
	if($num > 0){
		$response->getBody()->write("用户名已存在");
		return $response;
	}
	$create = "insert into test_register values(0,'$user','$pass')";
	$resultAdd = mysql_query($create);
	$no = mysql_affected_rows();//获得更新的行数
	if($no > 0){
		$response->getBody()->write("用户{$user}注册成功");	//普通字符串中需要用{}来明确变量的范围。这个{}不会做额外的输出。而''虽然也用明确变量范围的作用，但是会造成额外的字符输出
		return $response;
	}else{
		$response->getBody()->write("用户{$user}注册失败");
		return $response;
	}
});

$app->post('/login', function (Request $request,Response $response,$args) {
	$user = $request->getParam('user'); //checks both _GET and _POST [NOT PSR-7 Compliant]
	$pass = $request->getParsedBody()['pass']; //checks _POST  [IS PSR-7 compliant]
// 	$myvar3 = $request->getQueryParams()['myvar']; //checks _GET [IS PSR-7 compliant]
	if(strlen($user) == 0){
		$response->getBody()->write("用户名不能为空");
		return $response;
	}
	if(strlen($pass) == 0){
		$response->getBody()->write("密码不能为空");
		return $response;
	}
	$search = "select * from test_register where nickname='$user' and password='$pass'";//表示sql语句中的字符串外面的的单引号一定要有。不但符合sql语句的规则，而且还可以明确变量的范围，因此不用额外再加{}来表示变量
	$dbConnection = getDbLink();
	if(!$dbConnection){
		//获取连接失败
		$response->getBody()->write("数据库连接失败");
		return $response;
	}
	$result = mysql_query($search);
	$num = mysql_num_rows($result);//选择的行数
	echo("num = ");
	var_dump($num);
	echo("<br/>");
	if($num > 0){
		$response->getBody()->write("登录成功");
		return $response;
	}else{
		$response->getBody()->write("登录失败");
		return $response;
	}
});
$app->run();

