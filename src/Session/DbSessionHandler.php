<?php

namespace Bence\Session;

use Bence\Database\Database;

/**
 * Class DbSessionHandler
 *
 * @author Bence Borbély
 */
class DbSessionHandler implements \SessionHandlerInterface
{

    /**
     * @var Database
     */
    protected $db;

    /**
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    /**
     * @param string $save_path
     * @param string $session_id
     * @return bool
     */
    public function open($save_path, $session_id)
    {
        if($this->db){
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->db->close();
    }

    /**
     * @param string $session_id
     * @return string
     */
    public function read($session_id)
    {
        $this->db->query('SELECT data FROM sessions WHERE id = :id');
        $this->db->bind(':id', $session_id);

        if($this->db->execute()){
            $row = $this->db->single();
            return $row['data'];
        }

        return '';
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        $access = time();

        $this->db->query('REPLACE INTO sessions VALUES (:id, :access, :data)');
        $this->db->bind(':id', $session_id);
        $this->db->bind(':access', $access);
        $this->db->bind(':data', $session_data);

        if($this->db->execute()){
            return true;
        }

        return false;
    }

    /**
     * @param int $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        $this->db->query('DELETE FROM sessions WHERE id = :id');
        $this->db->bind(':id', $session_id);

        if($this->db->execute()){
            return true;
        }

        return false;
    }

    /**
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $old = time() - $maxlifetime;

        $this->db->query('DELETE * FROM sessions WHERE access < :old');
        $this->db->bind(':old', $old);

        if($this->db->execute()){
            return true;
        }

        return false;
    }

}
