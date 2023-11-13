<?php
namespace App\Library\Services;

class MsSQLGeneric
{
    /**
     * This is a custom service developed to integrate connection with MS SQL Server of Sutlej data connections.
     * @access Rights : Code Level
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $serverName = "";
	protected $dbName = "";
	protected $userId = "";
	protected $password = "";
	protected $connectionOptions = array();
	protected $conn = null;
	protected $table = null;
	protected $select = null;
	protected $where = null;

    /**
    * @Initialization constructor
    * Checks & develops a connection with Sutlej Database.
    */
	public function __construct()
	{
        $this->serverName = env('MS_SQL_SERVERNAME');
		$this->dbName = env('MS_SQL_DBNAME');
		$this->userId = env('MS_SQL_USERNAME');
		$this->password = env('MS_SQL_PASSWORD');
		try {
			$this->connectionOptions = array(
	   		"Database" => $this->dbName,
	   		"Uid" => $this->userId,
	   		"PWD" => $this->password
            );
			$this->conn = sqlsrv_connect($this->serverName, $this->connectionOptions);
			if( $this->conn === false ) {
			    //die( print_r( sqlsrv_errors(), true));
			}
		} catch(Exception $e) {
			return $this->conn;
		}
	} // end : construct

    /** This is a generic custom query builder function which returns array of data from Sutlej database.
     * @param TableName : Mandatory
     * @param Select : Select statement which by default remains "*"
     * @param Where : Where statement which by default remains blank.
     * @param Limit : Limit by default remains null.
     * @param Offset : Offset by default remains null.
     * @return Result : Array
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
	public function generic_query(String $table,$select = '*',$where = '',String $limit = null,String $offset = null)
	{
		$sql = "select ".$select." from ".$table;
		if(!empty($where)){
			$sql.=" where $where";
		}
		if($limit && $offset) {
			$sql.=" limit $offset,$limit";	
		}
		$stmt = sqlsrv_query( $this->conn, $sql );
		$result = array();
		if($stmt){
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC )) 
			{
	    	$result[] = $row;
			}	
		}else {
			//die( print_r( sqlsrv_errors(), true));
		}
		return $result;
    }
    
    /** This function returns connection object on being called.
     * @return Object
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
	public function getConnection()
	{
		return $this->conn;
	} // end : getConnection
}
?>