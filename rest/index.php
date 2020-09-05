<?php
require_once('../vendor/autoload.php');
require_once('config.php');
require_once('dao/AdminDao.class.php');
require_once('dao/UserDao.class.php');

Flight::register('admin_dao','AdminDao');
Flight::register('user_dao','UserDao');

use \Firebase\JWT\JWT;

Flight::route('POST /Adminlogin', function(){
        $user = Flight::request()->data->getData();
        $db_user = Flight::admin_dao()->get_admin_by_email($user['email']);
      
        if ($db_user){
          if ($db_user['password'] == $user['password']){
            //Flight::json($db_user); wrong
            $token_user = [
              'id' => $db_user['id'],
              'email' => $db_user['email'],
              
            ];
            $jwt = JWT::encode($token_user, Config::JWT_SECRET);
            Flight::json(['id' => $db_user['id'],'type' => $user['type'],'token' => $jwt]);
          }else{
            Flight::halt(404, 'Password Incorrect');
          }
        }else{
          Flight::halt(404, 'User not found');
        }
      });

      Flight::route('POST /Userlogin', function(){
        $user = Flight::request()->data->getData();
        $db_user = Flight::user_dao()->get_user_by_email($user['email']);
      
        if ($db_user){
          if ($db_user['password'] == $user['password']){
            //Flight::json($db_user); wrong
            $token_user = [
              'id' => $db_user['id'],
              'email' => $db_user['email'],
            ];
            $jwt = JWT::encode($token_user, Config::JWT_SECRET);
            Flight::json(['id' => $db_user['id'],'type' => $user['email'],'token' => $jwt]);
          }else{
            Flight::halt(404, 'Password Incorrect');
          }
        }else{
          Flight::halt(404, 'User not found');
        }
      });


      Flight::route('POST /UserRegister', function(){
        $user = Flight::request()->data->getData();
        Flight::user_dao()->add($user);
        
      });
      

      Flight::route('POST /AdminRegister', function(){
        $user = Flight::request()->data->getData();
        Flight::admin_dao()->add($user);
      });

      Flight::start();
?>