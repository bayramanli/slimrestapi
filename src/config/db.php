<?php
session_start();

class DB
{
    private $db;
    private $dbhost = DBHOST;
    private $dbuser = DBUSER;
    private $dbpass = DBPASS;
    private $dbname = DBNAME;

    public function __construct()
    {
        try {
            $this->db = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', $this->dbuser, $this->dbpass);
        } catch (Exception $e) {
            die("Bağlantı Başarısız:".$e->getMessage());
        }
    }

    public function addValue($argse)
    {
        $values = implode(',', array_map(function ($item) {
            return $item . '=?';
        }, array_keys($argse)));

        return $values;
    }

    public function insert($table, $values, $options = [])
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO $table SET {$this->addValue($values)}");
            $stmt->execute(array_values($values));

            if ($stmt->rowCount()) {
                $id = $this->db->lastInsertId();
                return ['status' => TRUE, 'id' => $id];
            } else {
                return ['status' => FALSE, 'error' => "Kayıt Başarısız"];
                exit;
            }
        } catch (Exception $e) {
            return ['status' => FALSE, 'error' => $e->getMessage()];
        }
    }


    public function update($table, $values, $options = [])
    {
        try {
            $columns_id = $values[$options['columns']];
            unset($values[$options['form_name']]);
            unset($values[$options['columns']]);
            $valuesExecute = $values;
            $valuesExecute += [$options['columns'] => $columns_id];

            $stmt = $this->db->prepare("UPDATE $table SET {$this->addValue($values)} WHERE {$options['columns']}=?");
            $stmt->execute(array_values($valuesExecute));

            if ($stmt->rowCount() > 0) {
                return ['status' => TRUE];
            } else {
                throw new Exception('İşlem Başarısız');
            }
        } catch (Exception $e) {
            return ['status' => FALSE, 'error' => $e->getMessage()];
        }
    }


    public function delete($table, $columns, $values, $fileName = null)
    {
        try {
            if (!empty($fileName)) {
                unlink("/images/$table/" . $fileName);
            }

            $stmt = $this->db->prepare("DELETE FROM $table WHERE $columns=?");
            $stmt->execute([htmlspecialchars($values)]);

            if ($stmt->rowCount() > 0) {
                return ['status' => TRUE];
            } else {
                throw new Exception('İşlem Başarısız');
            }
        } catch (Exception $e) {
            return ['status' => FALSE, 'error' => $e->getMessage()];
        }
    }

    public function read($table, $options = [])
    {
        try {
            if (isset($options['columns_name']) && empty($options['limit'])) {

                $stmt = $this->db->prepare("SELECT * FROM $table order by {$options['columns_name']} {$options['columns_sort']}");

            } else if (isset($options['columns_name']) && isset($options['limit'])) {
                if (isset($options['limitstart']) && isset($options['limitend'])) {
                    $stmt = $this->db->prepare("SELECT * FROM $table order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limitstart']}, {$options['limitend']}");
                } else {
                    $stmt = $this->db->prepare("SELECT * FROM $table order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limit']}");
                }
            } else {
                $stmt = $this->db->prepare("SELECT * FROM $table");
            }

            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function wread($table, $columns, $values, $options = [])
    {
        try {
            if (isset($options['columns_name']) && empty($options['limit'])) {
                $stmt = $this->db->prepare("SELECT * FROM $table WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']}");
            } else if (isset($options['columns_name']) && isset($options['limit'])) {
                $stmt = $this->db->prepare("SELECT * FROM $table WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limit']}");
            } else {
                $stmt = $this->db->prepare("SELECT * FROM $table WHERE $columns=?");
            }
            $stmt->execute([htmlspecialchars($values)]);
            return $stmt;
        } catch (Exception $e) {
            return ['status' => FALSE, 'error' => $e->getMessage()];
        }
    }

    public function qSql($sql, $options = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            return ['status' => FALSE, 'error' => $e->getMessage()];
        }
    }
}