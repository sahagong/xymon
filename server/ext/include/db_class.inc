<?
class my_db
{

	var $sqlhost;
	var $sqluser;
	var $sqlpass;
	var $sqldb;
	var $linkid;
	var $err;
	var $err_no;
	var $page;

	// Error 출력 function
	function error($text, $query)
	{

		$no = mysql_errno();
		$msg = mysql_error();
		$msg = urlencode($msg);
		$query = urlencode($query);
		//echo "<meta http-equiv='Refresh' content='0; URL=/dbfail.php?no=$no&msg=$msg&page=$this->page&query=$query'>";
		//echo "[$text] ( $no : $msg )<BR>\n";
		exit;

	}

	// DB 접속 function
	function connect()
	{

	   $this->linkid=mysql_connect($this->sqlhost,$this->sqluser,$this->sqlpass);
	   if (!$this->linkid)
	   {
		  $this->error("Connection attempt failed", "");
		  return;
	   }

	   $result=mysql_select_db($this->sqldb,$this->linkid);

	   if (!$result)
	   {
		  $this->error("Selection database failed", "");
		  return;
	   }

	}

	// select query function
	function select($query="")
	{

		$result = mysql_query($query);

		//print_r(mysql_free_result($result));


		if(!$result) {
			$this->error("Selection failed",$query);
			mysql_free_result($result);
			return false;
		}

		$cnt = 0;
		$data = array();


		while ( $row = mysql_fetch_array($result))
		{
			$data[$cnt] = $row;
			$cnt++;
		}

		mysql_free_result($result);

		#print_r($data);
		return $data;
	}

	// insert query function
	function insert($query="")
	{
		$result = mysql_query($query)||die(mysql_error());

		if(!$result) {
			$this->error("Insert failed",$query);
			return false;
		}

		$insert_id = mysql_insert_id();
		return $insert_id;
	}

	// delete query function
	function delete($query="")
	{

		$result = mysql_query($query);

		if(!$result) {
			$this->error("Delete failed",$query);
			return false;
		}

		return $true;
	}

	// update query function
	function update($query="")
	{

		$result = mysql_query($query);

		if(!$result) {
			$this->error("Update failed",$query);
			return false;
		}

		return $true;
	}

	function createTBL($query="")
	{
		$result = mysql_query($query);

		if(!$result) {
			$this->error("CreateTable failed",$query);
			return false;
		}

		return $true;

	}


	// DB close function
	function close()
	{

		mysql_close ($this->linkid);

	}

	// table 정보 function
	function list_fields($table)
	{

		$fields = mysql_list_fields($this->sqldb, $table, $this->linkid);

		$columns = mysql_num_fields($fields);

		for ($i = 0; $i < $columns; $i++) {

			$list_fields[$i] = mysql_field_name($fields, $i);
		}

		return $list_fields;
	}

	// 데이타베이스 선택 function  2002.06.12  added by sjjeon ..
	function select_db($dbname)
	{

		$this->sqldb = $dbname;
		$result=mysql_select_db($this->sqldb,$this->linkid);

		if (!$result)
		{
			$this->error("Selection database failed");
			return;
		}

	}
}





class ora_db
{

	var $sqlhost;
	var $sqluser;
	var $sqlpass;
	var $sqldb;
	var $linkid;
	var $sqldoc_exe = 0;
	var $error_event = FALSE;
	var $page;

	// Error 출력 function
	function error($text,$query)
	{

		$no = $this->error_event["code"];
		$msg = $this->error_event["message"];
		$msg = urlencode($msg);
		$query = urlencode($query);
		echo "<meta http-equiv='Refresh' content='0; URL=/dbfail.php?no=$no&msg=$msg&page=$this->page&query=$query'>";
		//echo "[$text] ( $no : $msg )<BR>\n";
		$this->close();
		exit;

	}

	// memory return function
	function freeStatement($free)
	{

		OCIFreeStatement($free);

	}

	// DB 접속 function
	function connect()
	{

		$this->linkid=OCILogon($this->sqluser,$this->sqlpass,$this->sqldb);
		if (!$this->linkid)
		{
			$this->error("Connection attempt failed", "");
			return;
		}

	}

	// select query function
	function select($query="")
	{

		$sqldoc_exe = OCIParse($this->linkid, $query);
		OCIExecute($sqldoc_exe, OCI_DEFAULT);

		if ($this->error_event = OCIError($sqldoc_exe))
		{
			$this->error("Select attempt failed",$query);
			return;
		}

		$cnt = 0;
		$data = array();
		while (OCIFetchInto($sqldoc_exe, $result, OCI_ASSOC + OCI_RETURN_NULLS))
		{
				$data[$cnt] = $result;
				$cnt++;
		}

		$this->freeStatement($sqldoc_exe);

		return $data;

	}

	// insert query function
	function insert($query=""){

		$sqldoc_exe = OCIParse($this->linkid, $query);
		OCIExecute($sqldoc_exe, OCI_DEFAULT);

		if ($this->error_event = OCIError($sqldoc_exe))
		{
			$this->error("Insert attempt failed",$query);
			return;
		}

		$this->freeStatement($sqldoc_exe);

		return true;

	}

	// delete query function
	function delete($query=""){

		$sqldoc_exe = OCIParse($this->linkid, $query);
		OCIExecute($sqldoc_exe, OCI_DEFAULT);

		if ($this->error_event = OCIError($sqldoc_exe))
		{
			$this->error("Delete attempt failed",$query);
			return;
		}

		$this->freeStatement($sqldoc_exe);

		return true;
	}

	// update query function
	function update($query=""){

		$sqldoc_exe = OCIParse($this->linkid, $query);
		OCIExecute($sqldoc_exe, OCI_DEFAULT);

		if ($this->error_event = OCIError($sqldoc_exe))
		{
			$this->error("Update attempt failed",$query);
			return;
		}

		$this->freeStatement($sqldoc_exe);

		return true;

	}

	// DB close function
	function close(){

		if ($this->error_event) {
			OCIRollback($this->linkid);
		} else {
			OCICommit($this->linkid);
		}

		OCILogoff($this->linkid);

	}

}


?>
