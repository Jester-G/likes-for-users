<?php

class Thanks
{
    private $connection;
    private $table = 'thanks';

    public function __construct($db)
    {
        $this->connection = $db;
    }

    /**
     * Get a count of all likes for each user
     * @param $search
     * @param $page
     * @return mixed
     */
    public function getLikes($search, $page)
    {
        $search = $this->setSearch($search);
        $page = ($page - 1) * 20;

        $query = "SELECT u.name, COUNT($search) AS sum FROM {$this->table} AS t " .
                 "JOIN `users` AS u ON $search=u.id GROUP BY u.name ORDER BY sum DESC " .
                 "LIMIT 20 OFFSET $page";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get a count of all likes for each user sorted by date
     * @param $search
     * @param $page
     * @param $start
     * @param $end
     * @return mixed
     */
    public function getLikesByDate($search, $page, $start, $end)
    {
        $search = $this->setSearch($search);
        $page = ($page - 1) * 20;

        $condition = $this->setCondition($start, $end);

        $query = "SELECT u.name, COUNT(*) AS sum FROM {$this->table} AS t " .
            "JOIN `users` AS u ON $search=u.id WHERE " . $condition . " GROUP BY u.name ORDER BY sum DESC " .
            "LIMIT 20 OFFSET $page";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get a count of all likes for each user sorted by department
     * If the params $start and $end are set, then sorting by date is added
     * @param $search
     * @param $page
     * @param $id
     * @param string $start
     * @param string $end
     * @return mixed
     */
    public function getLikesByDepartment($search, $page, $id, $start = '', $end = '')
    {
        $search = $this->setSearch($search);
        $page = ($page - 1) * 20;

        $condition = '';

        if ($start) {
            $condition = 'AND ' . $this->setCondition($start, $end);
        }

        $query = "SELECT u.name, COUNT(*) AS sum FROM {$this->table} AS t ".
                  "JOIN `users` AS u ON $search=u.id " .
                  "JOIN `department_user` AS du ON du.user_id=u.id " .
                  "WHERE du.department_id = :id $condition GROUP BY u.name ORDER BY sum DESC LIMIT 20 OFFSET $page";

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get a count of pages with all likes for each user
     * If the params $start and $end are set, then sorting by date is added
     * @param $search
     * @param string $start
     * @param string $end
     * @return false|float
     */
    public function getPagesCount($search, $start = '', $end = '')
    {
        $search = $this->setSearch($search);
        $condition = '';

        if ($start) {
            $condition = 'WHERE ' . $this->setCondition($start, $end);
        }

        $query = "SELECT COUNT(*) AS cnt FROM {$this->table} AS t " .
                 "JOIN `users` AS u ON $search=u.id " .$condition ." GROUP BY u.name;";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->rowCount();

        return ceil($result / 20);
    }

    /**
     * Get a count of pages with all likes for each user sorted by department
     * If the params $start and $end are set, then sorting by date is added
     * @param $search
     * @param $id
     * @param string $start
     * @param string $end
     * @return false|float
     */
    public function getPagesCountForDepartment($search, $id, $start = '', $end = '')
    {
        $search = $this->setSearch($search);
        $condition = '';

        if ($start) {
            $condition = 'AND ' . $this->setCondition($start, $end);
        }

        $query = "SELECT u.name, COUNT(*) AS sum FROM {$this->table} AS t
                  JOIN `users` AS u ON $search=u.id
                  JOIN `department_user` AS du ON du.user_id=u.id
                  WHERE du.department_id = :id $condition GROUP BY u.name";

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->rowCount();

        return ceil($result / 20);
    }

    /**
     * Set a condition for sorting by date
     * @param $start
     * @param $end
     * @return string
     */
    private function setCondition($start, $end)
    {
        return "t.date > '$start' AND t.date < '$end 23:59:59'";
    }

    /**
     * Set the field for the table 'thanks'
     * @param $search
     * @return string
     */
    private function setSearch($search)
    {
        if (strtolower($search) === 'from') {
            return 't.user_from_id';
        }

        return 't.user_to_id';
    }
}
