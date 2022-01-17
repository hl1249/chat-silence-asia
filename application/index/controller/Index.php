<?php

namespace app\index\controller;

// 使用db
use think\DB;

//控制器父类
use think\Controller;

// 接收参数
use think\Request;

// Worker
// use Workerman;
// use Workerman\Worker;

// 引入Index模型
use app\index\model\Index as IndexModel;

class Index extends Controller
{
	public function time(Request $request){
		// 获得当前时间戳
		return time();
	}
	
    public function index(Request $request)
    {
        $method = $request->method();//获取上传方式

        $request->param();//获取所有参数，最全

        $get = $request->get();//获取get上传的内容

        $post = $request->post();//获取post上传的内容

        $request->file('file'); //获取文件

        exit(json_encode($get, JSON_UNESCAPED_UNICODE));

        dump($get);
    }

    public function hello($name = 'ThinkPHP5')
    {
        print_r($_COOKIE);
        
        return;
        
        // return 'hello,' . $name;
        $aa = Db::table('think_user')->where('id', 1)->find();

        exit(json_encode($aa, JSON_UNESCAPED_UNICODE));

        dump($aa);
    }
  
	// 注册
    public function register(Request $request)
					
    {
		
        if ($request->post()) {
			$data = $request->post();
			// return $this->setToken($request->post('account'));
			$tokens = $this->setToken($request->post('account'));
			$data['token'] = $tokens;
			
			$loginBlo = Db::table('login')->where('account',$data['account'])->count();
			
			
			// 邀请码验证
			$code = Db::table('Invitation')->value('code');
			if($data['code'] != $code){
				
				json(1,'邀请码不存在');
				
				return;
			}
			
			if($loginBlo > 0){
				json(1,'此账号已被注册');
			}else{
				
				foreach($data as $k=>$v){
					
					if($k == 'code'){
						unset($data[$k]);
					}
				}
				
				// 设置过期时间
				$data['login_out_time'] = (time()+(60*60*24*7));
				// 设置token
				$data['token'] =  $this->setToken($request->post('account'));
				
				$data['rols']  = 1;
				
				//参数注入数据库
				$res = Db::name('login')->insertGetId($data);
				
				if($res){
					
					// 注册成功后修改邀请码的值
					$randomCode = $this->getRandomString(8);
					Db::table('Invitation')->update(['id' => '1','code'=>$randomCode]);
					
					json(0,'注册成功',array('token'=>$tokens));
				}
			}
			return;
			
        } else {
            return $this->fetch();
        }
    }
	
	// 登录
    public function login(Request $request)
    {
		
		$data = $request->post();
        
        if ($request->post()) {
            
			// 查找账号是否存在
			$loginBlo = Db::table('login')->where('account',$data['account'])->count();
			
			// 账号存在
			if($loginBlo == 1){
				
				$loginBlo = Db::table('login')->where('account',$data['account'])->count();
				
				$password = Db::table('login')->where('account',$data['account'])->value('password');
				
				if($data['password'] == $password){
					// 设置token过期时间 +60 就是登录完后的60 * 60 *24秒 一天后失效
					Db::name('login')->where('account',$data['account'])->update(['login_out_time'=>(time()+(60*60*24*7))]);
					
					$tokens = $this->setToken($request->post('account'));
					
					Db::name('login')->where('account',$data['account'])->setField('token', $tokens);
					
					// 设置 cookie 用来聊天
				// 	$expire=time()+60*60*24*30;
				// 	setcookie("user", "runoob", $expire);
					
					json(0,'登录成功',array('token'=>$tokens));
				}else{
					json(1,'账号或密码错误');
				}
				
			}else{
				json(1,'此账号不存在');
			}
			
        } else {
            return $this->fetch();
        }
    }
    
	// 根据token返回数据
	public function getUserInfo(Request $request){
		
		$data = $request->get();
		
		$userInfo = Db::table('login')->where('token',$data['token'])->find();
		
		// 如果当前时间大于 login_out_time 重新生成token 并退出登录
		$tokens = $this->setToken($userInfo['account']);
		if(time() > $userInfo['login_out_time']){
			Db::name('login')->where('account',$userInfo['account'])->update(['token'=>$tokens]);
			return json(1,'登录TOKEN超时');
		}
		
		$InvitationCode = "";
		if($userInfo['rols'] == 0){
		    $InvitationCode = Db::table('Invitation')->value('code');
		}
		
		// dump($userInfo);die;
		if($userInfo){
			json(0,'个人信息获取成功',array(
			'account'=>$userInfo['account'],
			'name'=>$userInfo['name'],
			'mobile'=>$userInfo['mobile'],
// 			'user'=>$_COOKIE["user"],
			'invitationCode'=>$InvitationCode,
			'avatar'=>$userInfo['avatar'],
			'avatar_change'=>$userInfo['avatar_change']
			
			));
		}else{
			json(1,'身份验证已过期');
		}
		
	}
	
    //渲染h5页面 	
	public function info(){
		return $this->fetch();
	}

    // 生成token
    public function setToken($phone)
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$phone);
        return $token;
    }
	
	// 生成随机字母+数字
	public function getRandomString($len, $chars=null){
		if(is_null($chars)){
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		}
		mt_srand(10000000*(double)microtime());
		for($i = 0,$str = '' , $lc = strlen($chars)-1; $i < $len; $i++){
			$str .= $chars[mt_rand(0,$lc)];
		}
		return $str;
	}
	
	// 上传图片
	public function upload(){
	    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('image');
	    // 移动到框架应用根目录/uploads/ 目录下
	    $info = $file->move( './uploads');
	    if($info){
	        // 成功上传后 获取上传信息
	        // 输出 jpg
	  //       echo $info->getExtension();
	  //       echo $info->getSaveName();
	  //       echo $info->getFilename(); 
			
			echo 'http://'.$_SERVER['SERVER_NAME'].'/uploads/'.$info->getSaveName();
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    }
	}
	
	public function changeAvatar(Request $request){
	    
	    if($request->post()){
	        $data = $request->post();
	        
        	$change = Db::name('login')->where('token',$data['token'])->update(['avatar'=>$data["url"]]);
        	if($change == 1){
    	        $url = Db::table('login')->where('token',$data['token'])->value('avatar');
        	    
    		    json(0,'修改成功',array('url'=>$url));
        	}
	    }
	}
	
	public function test_model()
	{
		$obj = new IndexModel();//调用model内的方法 写法1
		$res = $obj->user();
		
		return $res;
	}
	
}

class user{
	
}