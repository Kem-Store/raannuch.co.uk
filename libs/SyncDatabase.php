<?php
class SyncDatabase
{
	protected $dbConnect;
	protected $isConfig;
	
	public function __construct()
	{
		$loadConfig = parse_ini_file('setting.ini', true);
		// Change Array Setting
		foreach($loadConfig as $isGroupConfig) { foreach($isGroupConfig as $name=>$value) { $this->isConfig[$name] = $value; } }

		try {
			$this->dbConnect = @mysql_connect($this->isConfig['host'], $this->isConfig['username'], $this->isConfig['password']);
			if (!$this->dbConnect) {
				throw new Exception('<strong>Error:</strong> '.mysql_error());
			} else {
				mysql_select_db($this->isConfig['dbname']);
				mysql_set_charset('UTF8',$this->dbConnect); 
			}
		} catch(Exception $e) {
			echo '<p>'.$e->getMessage().'</p>';
		}
	}
	
	public function Query($sqlString)
	{
		list($sqlType) = explode(' ',$sqlString);
		switch(strtolower($sqlType))
		{
			case 'select':
				$result = array();				
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL SELECT:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						while($tmpValue = mysql_fetch_array($tmpQuery)) { array_push($result, $tmpValue); }
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
				}
				if(preg_match('(count\(\*\))', strtolower($sqlString)) && isset($result[0]) ) { $result = $result[0][0]; }
				return $result;
			break;
			case 'insert':
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL INSERT:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return mysql_insert_id($this->dbConnect);
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
			case 'update':
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL UPDATE:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return mysql_affected_rows($this->dbConnect);
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
			default:
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL Query:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return true;
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
		}
	}

	public function __destruct()
	{
		@mysql_close($this->dbConnect);
	}
}
?>