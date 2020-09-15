<?php
require_once 'BaseDao.class.php';
class TicketsDao extends BaseDao
{
    public $table="tickets";
    public function __construct()
    {
        parent:: __construct($this->table);
    }

    public function update_student($student, $student_id){        

        $entity[':id'] = $student_id;   
        $query= 'UPDATE '.  $this->table . ' SET ';
        foreach ($student as $key => $value) {
          $query .= $key . '=:' . $key . ', ';
          $entity[':' . $key] = $value;
        }
        $query = rtrim($query,', ') . ' WHERE id=:id';
        return $this->update($entity, $query);
      }

      public function delete_student($ticket_id)
      {
        $entity[':id'] = $ticket_id;
        $query = "DELETE FROM " . $this->table ." WHERE id=:id";

        return $this->execute($entity,$query);

      }
}

?>