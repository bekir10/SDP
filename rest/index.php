<?php

require_once('../vendor/autoload.php');
require_once('config.php');
require_once('dao/AdminDao.class.php');
require_once('dao/UserDao.class.php');
require_once('dao/TicketsDao.class.php');
require_once('dao/MessageDao.class.php');


Flight::register('admin_dao','AdminDao');
Flight::register('user_dao','UserDao');
Flight::register('tickets_dao','TicketsDao');
Flight::register('message_dao','MessageDao');



use \Firebase\JWT\JWT;

Flight::before('start', function(&$params, &$output){
  $request = Flight::request();
  if (substr( $request->url, 0, 7 ) === '/admins'){
    $headers = getallheaders();
    $token = @$headers['ADMIN'];
    if ($token){
      try {
          $user = JWT::decode($token, Config::JWT_SECRET, array('HS256'));
          if ($user){
            Flight::set('start_ts', time());
            Flight::set('user', $user);
          }else{
            Flight::halt(403, "Unauthorized User - Token Sucks");
          }
      } catch (\Exception $e) {
        Flight::halt(403, "Unauthorized User - Token Sucks");
      }
    }else{
      Flight::halt(403, "Unauthorized User");
    }
  }
});

/*----------------------------delete button ------------------------ */

Flight::route("DELETE admin/ticket/@id",function($id) //deleting student
{
        
        Flight::tickets_dao()->delete_ticket($id);
});
/*----------------------------update button ------------------------ */
Flight::route("POST /update", function() //update student
{
        $request= Flight::request()->data->getData();

        


        $id = $request["id"];
        $request["Fname"] = $request["edit_name"];
        $request["Lname"] = $request["edit_surname"];
        $request["type"] = $request["edit_type"];
        $request["plate"] = $request["edit_plate"];
        $request["price"] = $request["edit_price"];
        unset($request["edit_name"],$request["edit_surname"],$request["edit_type"],$request["edit_plate"],$request["edit_price"]);
        
        Flight::tickets_dao()->update_ticket($request,$id);
        
        Flight::json("Updated");
});




/*----------------------------get admins ------------------------ */
Flight::route("GET /admins", function() 
{
    
        $admins=Flight::admin_dao()->get_all();
        Flight::json($admins);

});
/*----------------------------get messges ------------------------ */


Flight::route("GET /message", function() 
{
    
        $messages=Flight::message_dao()->get_all();
        Flight::json($messages);

});

/*----------------------------add message ------------------------ */

Flight::route("POST /message", function() 
{
  
        $request= Flight::request()->data->getData();
        $request["id"] = $request["id"];
        $request["email"] = $request["mail"];
        $request["subject"] = $request["subt"];
        $request["message"] = $request["msg"];
        $request["name"] = $request["m_name"];
        
        unset($request["mail"],$request["subt"],$request["msg"],$request["m_name"],$request["id"]);
        
        Flight::message_dao()->add($request);
        Flight::json("message added");
});


/*----------------------------add ticket ------------------------ */

Flight::route("POST /ticket", function() 
{
  
        $request= Flight::request()->data->getData();
        $request["id"] = $request["id"];
        $request["Fname"] = $request["first_name"];
        $request["Lname"] = $request["last_name"];
        $request["type"] = $request["type_of"];
        $request["plate"] = $request["car_plate"];
        $request["price"] = $request["ticket_price"];
        unset($request["first_name"],$request["last_name"],$request["type_of"],$request["car_plate"],$request["ticket_price"],$request["id"]);
        
        Flight::tickets_dao()->add($request);
        Flight::json("Ticket added");
});

/*----------------------------Admin login------------------------ */

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
            Flight::json(['id' => $db_user['id'],'email' => $user['email'],'type' => $user['type'],'token' => $jwt]);
          }else{
            Flight::halt(404, 'Password Incorrect');
          }
        }else{
          Flight::halt(404, 'User not found');
        }
      });

       /*----------------------------Admin Register------------------------ */

       Flight::route('POST /AdminRegister', function(){
        $user = Flight::request()->data->getData();
        Flight::admin_dao()->add($user);
      });






/*----------------------------info button ------------------------ */

Flight::route("GET /info&edit", function() 
{
        
        $id = Flight::request()->query['id'];
        $student=Flight::tickets_dao()->get_by_id($id);
        Flight::json($student); 
        
});

/*----------------------------get tickets ------------------------ */
Flight :: route("GET /tickets", function() 
{
        
        $tickets=Flight::tickets_dao()->get_all();
        Flight::json($tickets);

});
      /*----------------------------User login------------------------ */

      Flight::route('POST /Userlogin', function(){
        $user = Flight::request()->data->getData();
        $db_user = Flight::user_dao()->get_user_by_email($user['email']);
      
        if ($db_user){
          if ($db_user['password'] == $user['password']){
            $token_user = [
              'id' => $db_user['id'],
              'email' => $db_user['email'],
            ];
            $jwt = JWT::encode($token_user, Config::JWT_SECRET);
            Flight::json(['id' => $db_user['id'],'type' => $user['type'],'email' => $user['email'],'token' => $jwt]);
          }else{
            Flight::halt(404, 'Password Incorrect');
          }
        }else{
          Flight::halt(404, 'User not found');
        }
      });


      /*----------------------------User Register------------------------ */

      Flight::route('POST /UserRegister', function(){
        $user = Flight::request()->data->getData();
        Flight::user_dao()->add($user);
        
      });


      Flight::start();
      
     
?>