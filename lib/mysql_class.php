<?php
class dbm{
    /**
     * @var mysqli
     */
	private $db;
	private $rowsCount = 0;
	private $result;

    private $host;
    private $dbname;
    private $username;
    private $password;

	private function load_auth_param()
    {
        $this->host = '172.17.0.1';
        $this->dbname = 'myparts_dba';
        $this->username = 'myparts_usr';
        $this->password = 'sdF98723KJef82';
	}
	
	public function connect()
    {
		$this->load_auth_param();
		$this->db = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

        mysqli_set_charset($this->db, 'cp1251');
        mysqli_query($this->db, "SET NAMES 'cp1251' COLLATE 'cp1251_general_ci'");
        mysqli_query($this->db, "SET CHARACTER SET 'cp1251' COLLATE 'cp1251_general_ci'");
    }
	
	public function close()
    {
        mysqli_close($this->db);
	}

	public function num_rows($result)
    {
		$this->rowsCount = mysqli_num_rows($result);

		return $this->rowsCount;
	}

	public function query($query)
    {
		$this->result = mysqli_query($this->db, $query);

		if (mysqli_error($this->db) != "") {
		    print mysqli_error($this->db) . "<br>query=" . $query;
		}

		return $this->result;
	}

	public function result($result,$number,$field_name)
    {
        $rowsCount = mysqli_num_rows($result);
        if ($rowsCount && $number <= ($rowsCount-1) && $number >=0){
            mysqli_data_seek($result, $number);
            $resrow = is_numeric($field_name) ? mysqli_fetch_row($result) : mysqli_fetch_assoc($result);
            if (isset($resrow[$field_name])){
                return $resrow[$field_name];
            }
        }

        return false;
   	}

   	public function getDbName()
    {
        return $this->dbname;
    }

    function clear_param($value)
    {
        $value = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $value);
        return mysqli_real_escape_string($this->db,$value);
    }

//    function prepare_query()
//    {
//	    $query = "INSERT INTO `A_GUESTS` (`firstname`, `lastname`, `email`, `age`) VALUES (?, ?, ?, ?)";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("sssi",$firstname,$lastname,$email,$age);
//        $firstname = "John";
//        $lastname = "Doe";
//        $email = "john@example.com";
//        $age = 23;
//        $stmt->execute();
//        $stmt->close();
//	    return $query;
//    }

}

class db{

    /**
     * @var mysqli
     */
    private $db;
    private $rowsCount = 0;
    private $result;

    private $host;
    private $dbname;
    private $username;
    private $password;

	private function load_auth_param()
    {
		$this->host = '172.17.0.1';
		$this->dbname = 'toko_dba';
		$this->username = 'toko_usr';
		$this->password = 'Xm53R9H4znZda4YH'; 
	}

    public function connect()
    {
        $this->load_auth_param();
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

        mysqli_set_charset($this->db, 'cp1251');
        mysqli_query($this->db, "SET NAMES 'cp1251' COLLATE 'cp1251_general_ci'");
        mysqli_query($this->db, "SET CHARACTER SET 'cp1251' COLLATE 'cp1251_general_ci'");
    }

    public function close()
    {
        mysqli_close($this->db);
    }

    public function num_rows($result)
    {
        $this->rowsCount = mysqli_num_rows($result);

        return $this->rowsCount;
    }

    public function query($query)
    {
        $this->result = mysqli_query($this->db, $query);

        if (mysqli_error($this->db) != "") {
            echo mysqli_error($this->db) . "<br>query=" . $query;
        }

        return $this->result;
    }

    public function result($result, $number, $field_name)
    {
        $rowsCount = mysqli_num_rows($result);
        if ($rowsCount && $number <= ($rowsCount-1) && $number >=0){
            mysqli_data_seek($result, $number);
            $resrow = is_numeric($field_name) ? mysqli_fetch_row($result) : mysqli_fetch_assoc($result);
            if (isset($resrow[$field_name])){
                return $resrow[$field_name];
            }
        }

        return false;
    }

    public function getDbName()
    {
        return $this->dbname;
    }

    function clear_param($value)
    {
        $value = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $value);
        return mysqli_real_escape_string($this->db,$value);
    }

}
