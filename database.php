<?php


class Database
{
    public $db;
    private $tbName;

    public function __construct(string $user, string $pass, string $dbname, string $tbName, string $host = "127.0.0.1")
    {
        $this->tbName = $tbName;
        $this->db = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    }

    /**
     * @param string $orderCriteria example: "created_at DESC, id ASC, ..."
     * @return null|array
     */
    public function findAll(string $orderCriteria = ""): ?array
    {
        $sql = "SELECT * FROM " . $this->tbName;
        if (!empty($orderCriteria)) {
            $sql .= " ORDER BY $orderCriteria";
        }
        $sth = $this->db->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**          
     * @param int $id
     * @return null|array
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM " . $this->tbName . " WHERE id=:id";

        $data = ["id" => $id];

        $sth = $this->db->prepare($sql);
        $sth->execute($data);

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**       
     * @param array $findCriteria   
     * @return null|array
     */
    public function findBy(array $findCriteria, string $orderCriteria = "", ?int $limit = null): ?array
    {
        $sql = "SELECT * FROM " . $this->tbName . " WHERE ";

        $whereStm = "";
        foreach ($findCriteria as $k => $v) {
            if ($v instanceof DateTime) {

                $date = $v->format("Y-m-d H");
                $whereStm .= "$k LIKE '$date%'";
            } elseif (is_string($v)) {
                $whereStm .= "$k = '$v'";
            } elseif (is_array($v)) {
                $inArray = implode(",", $v);
                $whereStm .= "$k IN($inArray)";
            } else {
                $whereStm .= "$k = '$v'";
            }
            if ($k != array_key_last($findCriteria)) {
                $whereStm .= " AND ";
            }
        }
        $sql .= $whereStm;

        if (!empty($orderCriteria)) {
            $sql .= " ORDER BY $orderCriteria";
        }
        if (!is_null($limit)) {
            $sql .= " LIMIT $limit";
        }

        $sth = $this->db->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * This function is used to insert data in a database's table
     */
    public function insert(array $insertData): bool
    {

        $columns = implode(",", array_keys($insertData));
        $placeholder = implode(
            ",",
            array_map(function ($k) {
                return ":$k";
            }, array_keys($insertData))
        );

        $data = array_map(function ($v) {
            if ($v instanceof DateTime) {
                return $v->format("Y-m-d H:i:s");
            }
            return $v;
        }, $insertData);

        $sql = "INSERT INTO " . $this->tbName . " ($columns) VALUES($placeholder)";
        $sth = $this->db->prepare($sql);

        return $sth->execute($data);
    }

    /*
    *   This function is used to update only one database's record in table
    */
    public function update(int $id, ?string $jsonData = null, int $st = 1): bool
    {
        if (is_null($jsonData)) {
            $data = [
                "id" => $id,
                "st" => $st,
            ];
            $sql = "UPDATE " . $this->tbName . " SET `status` = :st WHERE id = :id";
        } else {
            $data = [
                "id" => $id,
                "jsonData" => $jsonData,
                "st" => $st,
            ];

            $sql = "UPDATE " . $this->tbName . " SET `json_data` = :jsonData, `status` = :st WHERE id = :id";
        }
        $sth = $this->db->prepare($sql);

        return $sth->execute($data);
    }

    /*
    *   This function is used to update all database's record in table
    */
    public function updateAll(array $insertData): bool
    {

        $columns = implode(",", array_keys($insertData));
        $prepareString = implode(
            ",",
            array_map(function ($k) {
                return "$k = :$k";
            }, array_keys($insertData))
        );

        $data = array_map(function ($v) {
            if ($v instanceof DateTime) {
                return $v->format("Y-m-d H:i:s");
            }
            return $v;
        }, $insertData);

        $sql = "UPDATE " . $this->tbName . " SET $prepareString";

        $sth = $this->db->prepare($sql);

        return $sth->execute($data);
    }

    /*
    *   This function is used to delete a database's record in table
    */
    public function delete(int $id): bool
    {
        $data = [
            "id" => $id
        ];
        $sql = "DELETE FROM " . $this->tbName . " WHERE id = :id";
        $sth = $this->db->prepare($sql);

        return $sth->execute($data);
    }
}
