<?php
class Person{
	 
	private $name;
	private $age;
	public $sex;
	private $fatherName;
	private $motherName = "韩梅梅";
	 
	public function __construct($name="", $sex="男", $age=22)
	{
		$this->name = $name;
		$this->sex = $sex;
		$this->age = $age;
	}
	
	/**
	 * 声明一个析构方法
	 */
	public function __destruct()
	{
		echo "我觉得我还可以再抢救一下，我的名字叫".$this->name."<br/>";
	}
	
	/**
	 * 声明此方法用来处理调用对象中不存在的方法
	 */
	function __call($funName, $arguments)
	{
		echo "你所调用的函数：" . $funName . "(参数：" ; // 输出调用不存在的方法名
		print_r($arguments); // 输出调用不存在的方法时的参数列表
		echo ")不存在！<br/>\n"; // 结束换行
	}
	
	/**
	 * 声明此方法用来处理调用对象中不存在的方法
	 */
	public static function __callStatic($funName, $arguments)
	{
		echo "你所调用的静态方法：" . $funName . "(参数：" ; // 输出调用不存在的方法名
		print_r($arguments); // 输出调用不存在的方法时的参数列表
		echo ")不存在！<br/>\n"; // 结束换行
	}
	
	/**
	 * 在类中添加__get()方法，在直接获取属性值时自动调用一次，以属性名作为参数传入并处理
	 * @param $propertyName
	 *
	 * @return int
	 */
	public function __get($propertyName)
	{
		if ($propertyName == "age") {
			if ($this->age > 30) {
				return $this->age - 10;
			} else {
				return $this->$propertyName;
			}
		} else {
			return $this->$propertyName;
		}
	}
	
	/**
	 * 声明魔术方法需要两个参数，真接为私有属性赋值时自动调用，并可以屏蔽一些非法赋值
	 * @param $property
	 * @param $value
	 */
	public function __set($property, $value) {
		if ($property=="age")
		{
			if ($value > 150 || $value < 0) {
				return;
			}
		}
		$this->$property = $value;
	}
	
	/**
	 * @param $content
	 *
	 * @return bool
	 */
	
	
	/**
	 * 私有成员!!!当用isset()查看public成员的时候，不会运行__isset
	 * @param unknown $content
	 */
	public function __isset($content) {
		echo "当在类外部使用isset()函数测定私有成员{$content}时，自动调用<br/>";
	}
	
	/**
	 * 
	 * 当unset()无法销毁对象中的属性，例如私有属性，保护属性，那么会自动加载对象中的__unset方法。给予补救的机会。
	 * @param $content
	 *
	 * @return bool
	 */
	public function __unset($content) {
		echo "当在类外部使用unset()函数来删除私有成员时自动调用的<br/>";
	}
	
	/**
	 * @return array
	 */
	public function __sleep() {
		echo "当在类外部使用serialize()时会调用这里的__sleep()方法<br/>";
		$this->name = base64_encode($this->name);
		return array('name', 'age'); // 这里必须返回一个数值，里边的元素表示返回的属性名称
	}
	
	/**
	 * __wakeup
	 */
	public function __wakeup() {
		echo "当在类外部使用unserialize()时会调用这里的__wakeup()方法<br/>";
		$this->name = '帅哥';
		$this->sex = '男';
		// 这里不需要返回数组
	}
	
	/**
	 * 类被当成字符串时的回应方法
	 * @return string
	 */
	public function __toString()
	{
		return 'go go go<br/>';
	}
	
	/**
	 * 调用函数的方式调用一个对象时的回应方法
	 */
	public function __invoke() {
		echo '这可是一个对象哦<br/>';
	}
	
	/**
	 * 调用var_export()导出类时，此静态方法会被调用。
	 * @param unknown $an_array
	 * @return Person
	 */
	public static function __set_state($an_array)
	{
		$a = new Person();
		$a->name = $an_array['name'];
		return $a;
	}
	
	/**
	 * 当对象复制完成时调用
	 */
	public function __clone()
	{
		echo __METHOD__."你正在克隆对象<br/>";
	}
	
	
	
	/**
	 * say 说话方法
	 */
	public function say()
	{
		echo "我叫：".$this->name."，性别：".$this->sex."，年龄：".$this->age."<br/>";
	}
	
// 	/**
// 	 * __debugInfo()，打印所需调试信息
// 	 * 注意：该方法在PHP 5.6.0及其以上版本才可以用，如果你发现使用无效或者报错，请查看啊你的版本。
// 	 * @return array
// 	 */
// 	public function __debugInfo() {
// 		//这里的 `**` 是乘方的意思，也是在PHP5.6.0及其以上才可以使用
// 		return [
// 				'propSquared' => $this->prop ** 2,
// 		];
// 	}
	
	//__autoload()，尝试加载未定义的类
// 	/**
// 	 * 文件autoload_demo.php
// 	 */
// 	function __autoload($className) {
// 		$filePath = "project/class/{$className}.php";
// 		if (is_readable($filePath)) {
// 			require($filePath);
// 		}
// 	}
	 
// 	if (条件A) {
// 		$a = new A();
// 		$b = new B();
// 		$c = new C();
// 		// … 业务逻辑
// 	} else if (条件B) {
// 		$a = newA();
// 		$b = new B();
// 		// … 业务逻辑
// 	}
}
