<?php

function user_get_logged_user(){
    global $db,$appuser;
    
    $appuser=0;
    if (isset($_COOKIE['app_email']) and strlen($_COOKIE['app_email'])>0){		
		$items = $db->query("SELECT * FROM users WHERE LOWER(email) = ? and pass= ?",$_COOKIE['app_email'], $_COOKIE['app_pass'])->fetchAll();
		if (count($items)>0){
			$appuser=$items[0];	
		}
	}
    return $appuser;
    
}
    
function user_process_login($vars){
    global $db;

	$ret['status']=0;
	$ret['error']='';
	
	$vars['email']=trim(strtolower($vars['email']));
	
    if (strlen($ret['error'])==0 and strlen($vars['email'])==0) {
        $ret['error']="You need to provide an email.";
        return $ret;
    }

    if (strlen($ret['error'])==0 and strlen($vars['pass'])==0) {
        $ret['error']="The password should be filled.";
        return $ret;
    }

    if (strlen($ret['error'])>0)return  $ret;
    
    //search for it in the database ?
	$items = $db->query("SELECT * FROM users WHERE LOWER(email) = ? and pass= ?",$vars['email'], md5($vars['pass']))->fetchAll();
	if (count($items)==0){
	        $ret['error']=LANG_INCORRECT_EMAIL_PASSWORD;
	        return $ret;
	}
	//For the sake of simplicity, log the user directly by setting their cookies..
	setcookie("app_email", $vars['email'], time()+(3600*24),"/");
	setcookie("app_pass", md5($vars['pass']), time()+(3600*24),"/");
	
	$ret['status']=1;
	$ret['is_admin'] = $items[0]['is_admin'];
	$ret['error']='';
	return $ret;
}

function user_process_signup($vars){
	global $db;
	
	$ret['status']=0;
	$ret['error']='';
	
	$vars['email']=trim(strtolower($vars['email']));
	
	// regular expression to check email syntax
	$pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}[a-z.]*$/";



    if (strlen($ret['error'])==0 and strlen($vars['email'])==0) {
        $ret['error']=LANG_YOU_NEED_TO_PROVIDE_EMAIL;
        return $ret;
    }

	if(!preg_match($pattern, $vars['email'])){
		$ret['error']="Enter valid email!";
        return $ret;
	}

    if (strlen($ret['error'])==0 and strlen($vars['name'])==0) {
        $ret['error']="You need to type in your name.";
        return $ret;
    }

    if (strlen($ret['error'])==0 and strlen($vars['pass'])==0) {
        $ret['error']="The password should be filled.";
        return $ret;
    }

    if (strlen($ret['error'])>0)return  $ret;
    //search for it in the database ?
	$items = $db->query("SELECT * FROM users WHERE LOWER(email) = ?",$vars['email'])->fetchAll();
	if (count($items)>0){
	        $ret['error']="There is already an account with this email address";
	        return $ret;
	}
	//Else, there is no users in the db with the same email
    $db->query("INSERT INTO users (name, email, pass) VALUES ( ?, ?, ? )", $vars['name'], $vars['email'], md5($vars['pass']));
				
	//log the user directly by setting their cookies..
	setcookie("app_email", $vars['email'], time()+(3600*24),"/");
	setcookie("app_pass", md5($vars['pass']), time()+(3600*24),"/");
	
	$ret['status']=1;
	$ret['error']='';
	return $ret;
}

function calc_KPIs(){
	global $db;

	$users = $db->query("SELECT * FROM users")->fetchAll();
	$ret['users_num'] = count($users);

	$items = $db->query("SELECT * FROM items")->fetchAll();
	$ret['users_num'] = count($items);

	$pending_items = $db->query("SELECT * FROM items WHERE status = pending")->fetchAll();
	$ret['users_num'] = count($pending_items);

	$completed_items = $db->query("SELECT * FROM items WHERE status = completed")->fetchAll();
	$ret['users_num'] = count($completed_items);

	return $ret;
}

function KPI_per_user($id){
	global $db;

	$pending_items = $db->query("SELECT * FROM items WHERE user_id = ? AND status = pending", $id)->fetchAll();
	$ret['users_num'] = count($pending_items);

	$completed_items = $db->query("SELECT * FROM items WHERE user_id = ? AND status = completed", $id)->fetchAll();
	$ret['users_num'] = count($completed_items);

	return $ret;
}

?>
