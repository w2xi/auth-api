<?php 

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;

class Test {
	public function index($id = null)
	{
		(new IDMustBePositiveInt())->goCheck();
	}
}