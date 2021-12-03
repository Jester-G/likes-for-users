<?php

class Department
{
    private $connection;
    private $table = 'department';

    public function __construct($db)
    {
        $this->connection = $db;
    }

    /**
     * Get all departments
     * @return mixed
     */
    public function getDepartment()
    {
        $query = "SELECT id, name FROM {$this->table}";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}