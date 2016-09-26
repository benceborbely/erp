<?php

namespace Bence\Database;

/**
 * Class Database
 *
 * @author Bence Borbély
 */
class Database
{

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var \PDO
     */
    private $dbh;

    /**
     * @var string
     */
    private $error;

    /**
     * @var \PDOStatement
     */
    private $stmt;

    /**
     * @param string $host
     * @param string $db
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $db, $user, $password)
    {
        $this->host = $host;
        $this->dbName = $db;
        $this->user = $user;
        $this->password = $password;

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;

        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new \PDO($dsn, $this->user, $this->password, $options);
        }
        catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * @return array
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     *
     */
    public function close()
    {
        $this->dbh = null;
        return true;
    }

}
