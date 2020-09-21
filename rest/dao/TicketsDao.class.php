<?php
require_once 'BaseDao.class.php';
class TicketsDao extends BaseDao
{
    public $table="tickets";
    public function __construct()
    {
        parent:: __construct($this->table);
    }

    public function update_ticket($ticket, $ticket_id){        

        $entity[':id'] = $ticket_id;   
        $query= 'UPDATE '.  $this->table . ' SET ';
        foreach ($ticket as $key => $value) {
          $query .= $key . '=:' . $key . ', ';
          $entity[':' . $key] = $value;
        }
        $query = rtrim($query,', ') . ' WHERE id=:id';
        return $this->update($entity, $query);
      }

      public function delete_ticket($ticket_id)
      {
        $entity[':id'] = $ticket_id;
        $query = "DELETE FROM " . $this->table ." WHERE id=:id";

        return $this->execute($entity,$query);

      }
}

?>