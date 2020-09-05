<?php
require_once 'BaseDao.class.php';
class AdminDao extends BaseDao
{
    
    public function __construct()
    {
        parent:: __construct('admin');
    }

    public function get_admin_by_email($email){
        $query = "SELECT * FROM admin WHERE email=:email";
        return @($this->execute_query($query, ['email' => $email]))[0];
      }
}

?>