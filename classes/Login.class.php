<?php

use Database\TinyMVCDatabase as DB;

class Login extends Database\TinyMVCDatabaseObject{
    const tablename  = 'user';

    public function authorize($login, $password){
        global $subscription;
        $password_hashed = md5($password);
        
        try{
            $query = DB::createQuery()->select('id')->where('email=? AND password=? AND deleted=0'); 
            $users = $this->getList($query, [strval($login), $password_hashed]);
            
            if (count($users) === 0){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            
            $user_id = $users[0]->id;
            $user = $this->load($user_id);
            $subsc = $subscription->loadByRow("agency", $user->agency);
            $auth_token = bin2hex(openssl_random_pseudo_bytes(16));
            
            if ($subsc->suspended == 1){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            
            if (
                    $user->id == 4 || 
                    $user->authorized == 0 || 
                    ($user->authorized == 1 && User::notSeenTooLongMobile($user_id) == 1) ||
                    !isset($user->auth_token)
            ){ // разлогинен ИЛИ залогинен, но давно не заходил
                $_SESSION["user"] = $user_id;
                $user->authorized = 1;
                $user->lastseen = time();
                $user->mobile_lastseen = time();
                $user->auth_token = $auth_token;
                $user->save();
            }
            elseif ($user->authorized == 1 && !User::notSeenTooLongMobile($user_id) && $user->id != 4){ // залогинен и заходил недавно
                throw new Exception("forbidden_already_authorized", 501);
            }
            
            $response = array(
                "sUserId" => $user_id, 
                "iErrorCode" => 0, 
                "sErrorDesc" => "OK",
                "auth_token" => $auth_token
            );
        }
        catch(Exception $e){
            $response = array( 
                    'error' => array('code'  => $e->getCode(), 'description' => $e->getMessage() )
            );
        }

        return $response;
    }
    
    public function authorizeWithToken($token){
        global $subscription;
        
        $query = DB::createQuery()->select('id')->where('auth_token = ?'); 
        $users = $this->getList($query, [strval($token)]);

        if (count($users) === 0){
            return FALSE;
        }

        $user_id = $users[0]->id;
        $user = $this->load($user_id);
        $subsc = $subscription->loadByRow("agency", $user->agency);

        if ($subsc->suspended == 1){
            return FALSE;
        }

        $_SESSION["user"] = $user_id;
        $user->authorized = 1;
        $user->lastseen = time();
        $user->mobile_lastseen = time();
        $user->save();

        return TRUE;
    }
    
    public function authorizeApp($login, $password){
        global $subscription;
        $login = strval($login);
        $password = strval($password);
        
        try{
            $query = DB::createQuery()->select('id')->where('email=? AND password=? AND deleted=0'); 
            $users = $this->getList($query, [$login, $password]);
            
            if (count($users) === 0){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            
            $user_id = $users[0]->id;
            $user = $this->load($user_id);
            $subsc = $subscription->loadByRow("agency", $user->agency);
            $token = md5(rand(1, 1000000));
            
            if ($subsc->suspended == 1){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            elseif ($subscription->getVoipByUserId($user_id) == 0){
                throw new Exception("forbidden_voip_disabled", 501);
            }
            
            $_SESSION["user"] = $user_id;
            $user->rememberme_token = $token;
            $user->save();
            
            $response = array(
                "sUserId" => $user_id, 
                "sToken" => $token,
                "iErrorCode" => 0, 
                "sErrorDesc" => "OK"
            );
        }
        catch(Exception $e){
            $response = array( 
                'error' => array('code'  => $e->getCode(), 'description' => $e->getMessage() )
            );
        }

        return $response;
    }
    
    public function logout(){
        session_destroy();
        session_unset();
        unset($_SESSION["user"]);
        
        return 0;
    }
    
    public function test($login, $password){
        global $subscription;
        $login = strval($login);
        $password = strval($password);
        
        try{
            $query = DB::createQuery()->select('id')->where('email=? AND password=? AND deleted=0'); 
            $users = $this->getList($query, [$login, $password]);
            
            if (count($users) === 0){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            
            $user_id = $users[0]->id;
            $user = $this->load($user_id);
            $subsc = $subscription->loadByRow("agency", $user->agency);
            
            if ($subsc->suspended == 1){
                throw new Exception("forbidden_credentials_incorrect", 501);
            }
            
            if ($user->id == 4 || $user->authorized == 0 || ($user->authorized == 1 && User::notSeenTooLong($user_id) == 1)){ // разлогинен ИЛИ залогинен, но давно не заходил
                $_SESSION["user"] = $user_id;
                $user->authorized = 1;
                $user->lastseen = time();
                $user->save();
            }
            elseif ($user->authorized == 1 && !User::notSeenTooLong($user_id) && $user->id != 4){ // залогинен и заходил недавно
                throw new Exception("forbidden_already_authorized", 501);
            }
            
            $response = $user->name;
        }
        catch(Exception $e){
            $response = array( 
                    'error' => array('code'  => $e->getCode(), 'description' => $e->getMessage() )
            );
        }

        return $response;
    }

}
