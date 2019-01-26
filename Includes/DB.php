<?php

namespace Includes;


	if (!defined('PHP_SHLIB_SUFFIX'))
	{
		define('PHP_SHLIB_SUFFIX',strtoupper(substr(PHP_OS, 0,3)) == 'WIN' ? 'dll' : 'so');
	}
	if (!defined('PHP_SHLIB_PREFIX'))
	{
		define('PHP_SHLIB_PREFIX',PHP_SHLIB_SUFFIX == 'dll' ? 'php_' : '');
	}
	
	require_once( dirname(__FILE__).'/adodb5/adodb.inc.php' );	

	class DB
	{
		var $Type = '';
		var $setupType = '';
		var $Host     = '';
		var $Port     = '';
		var $Database = '';
		var $User     = '';
		var $Password = '';

		var $Auto_Free     = 0;

		var $Debug         = 0;

		var $Halt_On_Error = 'yes';

		var $Record   = array();

		var $Row;

		var $Errno    = 0;

		/**
		* @var string descriptive text from last error
		*/
		var $Error    = '';

		//i am not documenting private vars - skwashd :)
        var $xmlrpc = False;
		var $soap   = False;
		var $Link_ID = 0;
		var $privat_Link_ID = False;	// do we use a privat Link_ID or a reference to the global ADOdb object
		var $Query_ID = 0;

		/**
		 * @var array $capabilities, defaults will be changed be method set_capabilities($ado_driver,$db_version)
		 */
		var $capabilities = array(
			'sub_queries'      => true,	// will be set to false for mysql < 4.1
			'union'            => true, // will be set to false for mysql < 4.0
			'outer_join'       => false,	// does the DB has an outer join, will be set eg. for postgres
			'distinct_on_text' => true,	// is the DB able to use DISTINCT with a text or blob column
			'like_on_text'     => true,	// is the DB able to use LIKE with text columns
			'name_case'        => 'upper',	// case of returned column- and table-names: upper, lower(pgSql), preserv(MySQL)
			'client_encoding'  => false,	// db uses a changeable clientencoding
			'order_on_text'    => true,	// is the DB able to order by a given text column, boolean or
		);								// string for sprintf for a cast (eg. 'CAST(%s AS varchar)')

		var $prepared_sql = array();	// sql is the index

		/**
		* @param string $query query to be executed (optional)
		*/

		function db($query = '')
		{
			$this->query($query);
		}

		/**
		* @return int current connection id
		*/
		function link_id()
		{
			return $this->Link_ID;
		}

		/**
		* @return int id of current query
		*/
		function query_id()
		{
			return $this->Query_ID;
		}

		function connect($Database = NULL, $Host = NULL, $Port = NULL, $User = NULL, $Password = NULL,$Type = NULL)
		{
			/* Handle defaults */
			if (!is_null($Database) && $Database)
			{
				$this->Database = $Database;
			}
			if (!is_null($Host) && $Host)
			{
				$this->Host     = $Host;
			}
			if (!is_null($Port) && $Port)
			{
				$this->Port     = $Port;
			}
			if (!is_null($User) && $User)
			{
				$this->User     = $User;
			}
			if (!is_null($Password) && $Password)
			{
				$this->Password = $Password;
			}
			if (!is_null($Type) && $Type)
			{
				$this->Type = $Type;
			}
			elseif (!$this->Type)
			{
				$this->Type = 'mysql';
			}

			if (!$this->Link_ID)
			{
				foreach(array('Host','Database','User','Password') as $name)
				{
					$$name = $this->$name;
				}
				$this->setupType = $php_extension = $type = $this->Type;

				switch($this->Type)	// convert to ADO db-type-names
				{
					case 'pgsql':
						$type = 'postgres'; // name in ADOdb
						// create our own pgsql connection-string, to allow unix domain soccets if !$Host
						$Host = "dbname=$this->Database".($this->Host ? " host=$this->Host".($this->Port ? " port=$this->Port" : '') : '').
							" user=$this->User".($this->Password ? " password='".addslashes($this->Password)."'" : '');
						$User = $Password = $Database = '';	// to indicate $Host is a connection-string
						break;

					case 'odbc_mssql':
						$php_extension = 'odbc';
						$this->Type = 'mssql';
						// fall through
					case 'mssql':
						if ($this->Port) $Host .= ','.$this->Port;
						break;

					case 'odbc_oracle':
						$php_extension = 'odbc';
						$this->Type = 'oracle';
						break;
					case 'oracle':
						$php_extension = $type = 'oci8';
						break;

					case 'sapdb':
						$this->Type = 'maxdb';
						// fall through
					case 'maxdb':
						$type ='sapdb';	// name in ADOdb
						$php_extension = 'odbc';
						break;

					case 'mysqlt':
						$php_extension = 'mysql';	// you can use $this->setupType to determine if it's mysqlt or mysql
						// fall through
					case 'mysqli':
						$this->Type = 'mysql';
						// fall through
					default:
						if ($this->Port) $Host .= ':'.$this->Port;
						break;
				}
				if (!isset($GLOBALS['app']->ADOdb)) {
					$GLOBALS['app']->ADOdb = "";
				}
				if (!is_object($GLOBALS['app']->ADOdb) ||	// we have no connection so far
					(is_object($GLOBALS['app']->db) &&	// we connect to a different db, then the global one
						($this->Type != $GLOBALS['app']->db->Type ||
						$this->Database != $GLOBALS['app']->db->Database ||
						$this->User != $GLOBALS['app']->db->User ||
						$this->Host != $GLOBALS['app']->db->Host ||
						$this->Port != $GLOBALS['app']->db->Port)))
				{
					if (!extension_loaded($php_extension) && (!function_exists('dl') ||
						!dl(PHP_SHLIB_PREFIX.$php_extension.'.'.PHP_SHLIB_SUFFIX)))
					{
						$this->halt("Necessary php database support for $this->Type (".PHP_SHLIB_PREFIX.$php_extension.'.'.PHP_SHLIB_SUFFIX.") not loaded and can't be loaded, exiting !!!");
						return 0;	// in case error-reporting = 'no'
					}
					if (!isset($GLOBALS['app']->ADOdb)) {
						$GLOBALS['app']->ADOdb = "";
					}
					if (!is_object($GLOBALS['app']->ADOdb))	// use the global object to store the connection
					{
						$this->Link_ID = &$GLOBALS['app']->ADOdb;
					}
					else
					{
						$this->privat_Link_ID = True;	// remember that we use a privat Link_ID for disconnect
					}
					$this->Link_ID = ADONewConnection($type);
					if (!$this->Link_ID)
					{
						$this->halt("No ADOdb support for '$type' ($this->Type) !!!");
						return 0;	// in case error-reporting = 'no'
					}
					$connect = 'Connect';
					if (($Ok = $this->Link_ID->$connect($Host, $User, $Password)))
					{
						$this->ServerInfo = $this->Link_ID->ServerInfo();
						$this->set_capabilities($type,$this->ServerInfo['version']);
						$Ok = $this->Link_ID->SelectDB($Database);
					}
					if (!$Ok)
					{
						$this->halt("ADOdb::$connect($Host, $User, \$Password, $Database) failed.");
						return 0;	// in case error-reporting = 'no'
					}
					if ($this->Debug)
					{
						echo function_backtrace();
						echo "<p>new ADOdb connection to $this->Type://$this->Host/$this->Database: Link_ID".($this->Link_ID === $GLOBALS['app']->ADOdb ? '===' : '!==')."\$GLOBALS[egw]->ADOdb</p>";
						//echo "<p>".print_r($this->Link_ID->ServerInfo(),true)."</p>\n";
						_debug_array($this);
						echo "\$GLOBALS[egw]->db="; _debug_array($GLOBALS[egw]->db);
					}
					if ($this->Type == 'mssql')
					{
						// this is the format ADOdb expects
						$this->Link_ID->Execute('SET DATEFORMAT ymd');
						// sets the limit to the maximum
						ini_set('mssql.textlimit',2147483647);
						ini_set('mssql.sizelimit',2147483647);
					}
				}
				else
				{
					$this->Link_ID = &$GLOBALS['app']->ADOdb;
				}
			}
			// next ADOdb version: if (!$this->Link_ID->isConnected()) $this->Link_ID->Connect();
			if (!$this->Link_ID->_connectionID) $this->Link_ID->Connect();

			//echo "<p>".print_r($this->Link_ID->ServerInfo(),true)."</p>\n";
			return $this->Link_ID;
		}

		function set_capabilities($adodb_driver,$db_version)
		{
			switch($adodb_driver)
			{
				case 'mysql':
				case 'mysqlt':
				case 'mysqli':
					$this->capabilities['sub_queries'] = (float) $db_version >= 4.1;
					$this->capabilities['union'] = (float) $db_version >= 4.0;
					$this->capabilities['name_case'] = 'preserv';
					$this->capabilities['client_encoding'] = (float) $db_version >= 4.1;
					break;

				case 'postgres':
					$this->capabilities['name_case'] = 'lower';
					$this->capabilities['client_encoding'] = (float) $db_version >= 7.4;
					$this->capabilities['outer_join'] = true;
					break;

				case 'mssql':
					$this->capabilities['distinct_on_text'] = false;
					$this->capabilities['order_on_text'] = 'CAST (%s AS varchar)';
					break;

				case 'maxdb':	// if Lim ever changes it to maxdb ;-)
				case 'sapdb':
					$this->capabilities['distinct_on_text'] = false;
					$this->capabilities['like_on_text'] = (float) $db_version >= 7.6;
					$this->capabilities['order_on_text'] = false;
					break;
			}
			//echo "db::set_capabilities('$adodb_driver',$db_version)"; _debug_array($this->capabilities);
		}

		/**
		* Close a connection to a database
		*/
		function disconnect()
		{
			if (!$this->privat_Link_ID)
			{
				unset($GLOBALS['app']->ADOdb);
			}
			unset($this->Link_ID);
			$this->Link_ID = 0;
		}

		/**
		* Escape strings before sending them to the database
		*
		* @deprecated use quote($value,$type='') instead
		* @param string $str the string to be escaped
		* @return string escaped sting
		*/
		function db_addslashes($str)
		{
			if (!isset($str) || $str == '')
			{
				return '';
			}
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			return $this->Link_ID->addq($str);
		}

		function to_timestamp($epoch)
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			// the substring is needed as the string is already in quotes
			return substr($this->Link_ID->DBTimeStamp($epoch),1,-1);
		}

		function from_timestamp($timestamp)
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			return $this->Link_ID->UnixTimeStamp($timestamp);
		}

		function from_bool($val)
		{
			return $val && $val{0} !== 'f';	// everthing other then 0 or f[alse] is returned as true
		}

		function free()
		{
			unset($this->Query_ID);	// else copying of the db-object does not work
			$this->Query_ID = 0;
		}

		function query($Query_String, $line = '', $file = '', $offset=0, $num_rows=-1,$inputarr=false)
		{
			if ($Query_String == '')
			{
				return 0;
			}
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}

			# New query, discard previous result.
			if ($this->Query_ID)
			{
				$this->free();
			}
			if ($this->Link_ID->fetchMode != ADODB_FETCH_BOTH)
			{
				$this->Link_ID->SetFetchMode(ADODB_FETCH_BOTH);
			}
			if (!$num_rows)
			{
				$num_rows = $GLOBALS['plus_info']['user']['preferences']['common']['maxmatchs'];
			}
			if ($num_rows > 0)
			{
				$this->Query_ID = $this->Link_ID->SelectLimit($Query_String,$num_rows,(int)$offset,$inputarr);
			}
			else
			{
				$this->Query_ID = $this->Link_ID->Execute($Query_String,$inputarr);
			}
			$this->Row = 0;
			$this->Errno  = $this->Link_ID->ErrorNo();
			$this->Error  = $this->Link_ID->ErrorMsg();

			if (! $this->Query_ID)
			{
				$this->halt("Invalid SQL: ".(is_array($Query_String)?$Query_String[0]:$Query_String).
					($inputarr ? "<br>Parameters: '".implode("','",$inputarr)."'":''),
					$line, $file);
			}
			return $this->Query_ID;
		}

		function limit_query($Query_String, $offset, $line = '', $file = '', $num_rows = '',$inputarr=false)
		{
			return $this->query($Query_String,$line,$file,$offset,$num_rows,$inputarr);
		}

		function nextRecord($fetch_mode=ADODB_FETCH_BOTH)
		{
			if (!$this->Query_ID)
			{
				$this->halt('next_record called with no query pending.');
				return 0;
			}
			if ($this->Link_ID->fetchMode != $fetch_mode)
			{
				$this->Link_ID->SetFetchMode($fetch_mode);
			}
			if ($this->Row)	// first row is already fetched
			{
				$this->Query_ID->MoveNext();
			}
			++$this->Row;

			$this->Record = $this->Query_ID->fields;

			if ($this->Query_ID->EOF || !$this->Query_ID->RecordCount() || !is_array($this->Record))
			{
				return False;
			}
			if ($this->capabilities['name_case'] == 'upper')	// maxdb, oracle, ...
			{
				switch($fetch_mode)
				{
					case ADODB_FETCH_ASSOC:
						$this->Record = array_change_key_case($this->Record);
						break;
					case ADODB_FETCH_NUM:
						$this->Record = array_values($this->Record);
						break;
					default:
						$this->Record = array_change_key_case($this->Record);
						if (!isset($this->Record[0]))
						{
							$this->Record += array_values($this->Record);
						}
						break;
				}
			}
			return True;
		}

		function seek($pos = 0)
		{
			if (!$this->Query_ID  || !$this->Query_ID->Move($this->Row = $pos))
			{
				$this->halt("seek($pos) failed: resultset has " . $this->num_rows() . " rows");
				$this->Query_ID->Move( $this->num_rows() );
				$this->Row = $this->num_rows();
				return False;
			}
			return True;
		}

		function transaction_begin()
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			//return $this->Link_ID->BeginTrans();
			return $this->Link_ID->StartTrans();
		}

		function transaction_commit()
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			//return $this->Link_ID->CommitTrans();
			return $this->Link_ID->CompleteTrans();
		}

		function transaction_abort()
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			//return $this->Link_ID->RollbackTrans();
			return $this->Link_ID->FailTrans();
		}

		function get_last_insert_id($table, $field)
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			$id = $this->Link_ID->PO_Insert_ID($table,$field);	// simulates Insert_ID with "SELECT MAX($field) FROM $table" if not native availible

			if ($id === False)	// function not supported
			{
				echo "<p>db::get_last_insert_id(table='$table',field='$field') not yet implemented for db-type '$this->Type' OR no insert operation before</p>\n";
				function_backtrace();
				return -1;
			}
			return $id;
		}

		function lock($table, $mode='write')
		{}

		function unlock()
		{}

		function affected_rows()
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			return $this->Link_ID->Affected_Rows();
		}

		function num_rows()
		{
			return $this->Query_ID ? $this->Query_ID->RecordCount() : False;
		}

		function num_fields()
		{
			return $this->Query_ID ? $this->Query_ID->FieldCount() : False;
		}

		/**
		* @deprecated use num_rows()
		*/
		function nf()
		{
			return $this->num_rows();
		}

		/**
		* @deprecated use print num_rows()
		*/
		function np()
		{
			print $this->num_rows();
		}

		/**
		* Return the value of a column
		*
		* @param string/integer $Name name of field or positional index starting from 0
		* @param bool $strip_slashes string escape chars from field(optional), default false
		*	depricated param, as correctly quoted values dont need any stripslashes!
		* @return string the field value
		*/
		function f($Name, $strip_slashes = False)
		{
			if ($strip_slashes)
			{
				return stripslashes($this->Record[$Name]);
			}
			return $this->Record[$Name];
		}

		function decodeHTML($string) {
			$entities = array_flip(get_html_translation_table(HTML_ENTITIES));
			array_shift($entities); // shift off &nbsp;
			$string = strtr($string, $entities);
			$string = preg_replace("/&#([0-9]+);/me", "chr('\\1')", $string);
			return $string;
		}
		
		/**
		* Print the value of a field
		*
		* @param string $Name name of field to print
		* @param bool $strip_slashes string escape chars from field(optional), default false
		*	depricated param, as correctly quoted values dont need any stripslashes!
		*/
		function p($Name, $strip_slashes = True)
		{
			print $this->f($Name, $strip_slashes);
		}

		/**
		* Returns a query-result-row as an associative array (no numerical keys !!!)
		*
		* @param bool $do_next_record should nextRecord() be called or not (default not)
		* @param string $strip='' string to strip of the column-name, default ''
		* @return array/bool the associative array or False if no (more) result-row is availible
		*/
		function row($do_next_record=False,$strip='')
		{
			if ($do_next_record && !$this->nextRecord(ADODB_FETCH_ASSOC) || !is_array($this->Record))
			{
				return False;
			}
			$result = array();
			foreach($this->Record as $column => $value)
			{
				if (!is_numeric($column))
				{
					if ($strip) $column = str_replace($strip,'',$column);

					$result[$column] = $value;
				}
			}
			return $result;
		}

		/**
		* Error handler
		*
		* @param string $msg error message
		* @param int $line line of calling method/function (optional)
		* @param string $file file of calling method/function (optional)
		*/
		function halt($msg, $line = '', $file = '')
		{
			$error_msg = $msg;
			
			if ($this->Link_ID)		// only if we have a link, else infinite loop
			{
				$this->Error = $this->Link_ID->ErrorMsg();	// need to be BEFORE unlock,
				$this->Errno = $this->Link_ID->ErrorNo();	// else we get its error or none

				$this->unlock();	/* Just in case there is a table currently locked */
			}
			
			$error_no = time();

			$error_msg .= '[' . date('Y/m/d H:i:s') . "]\n";
			$error_msg .= 'Error No: ' . $error_no . "\n";
			
			if ($this->Halt_On_Error == "no")
			{
				return;
			}
			
			$db_msg = $this->haltmsg($msg);

			if ($file)
			{
				$error_msg .= sprintf("File: %s \n",$file);
			}
			if ($line)
			{
				$error_msg .= sprintf("Line: %s \n",$line);
			}
			

			//$error_msg .= print_r($_REQUEST, true);			
			//$error_msg .= sprintf("\nFunction: %s\n",function_backtrace(2));
			//$error_msg .= format_backtrace(debug_backtrace(), $file, $line, $db_msg);
			error_log($error_msg);
			$html = '	<meta charset="utf-8" />
							<link rel="stylesheet" type="text/css" href="'. $GLOBALS['app']->link('/Resources/semantic-ui/semantic.min.css') .'">
							<div class="ignored ui warning message" id="appMessage">
								<i class="comment outline icon"></i>
								interrup : '. $error_no .'
							</div>';
			die($html);
			
			if ($this->Halt_On_Error != "report")
			{
				error_log($error_msg);
				$html = '	<meta charset="utf-8" />
							<link rel="stylesheet" type="text/css" href="'. $GLOBALS['app']->link('/Resources/semantic-ui/semantic.min.css') .'">
							<div class="ignored ui warning message hidden" id="appMessage">
								<i class="comment outline icon"></i>
								interrup : '. $error_no .'
							</div>';
				die();
				
				//echo "<div class='fatalerror'><b>".Lang('A system error has been detected, please contact the administrator with error number') . ': ' .$error_no."</b></div>";
				// if (is_object($GLOBALS['app']->common))
				// {
				// 	//$GLOBALS['app']->common->egw_exit(True);
				// }
				// else	// happens eg. in setup
				// {
				// 	//exit();
				// }
			}
		}

		function haltmsg($msg)
		{
			$error_msg = '';
			
			$error_msg = sprintf("Database error: %s\n", $msg);
			if (($this->Errno || $this->Error) && $this->Error != "()")
			{
				$error_msg = sprintf("$this->Type Error</b>: %s (%s)\n",$this->Errno,$this->Error);
			}
			
			return $error_msg;
		}

		function metadata($table='',$full=false)
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			$columns = $this->Link_ID->MetaColumns($table);
			//$columns = $this->Link_ID->MetaColumnsSQL($table);
			//echo "<b>metadata</b>('$table')=<pre>\n".print_r($columns,True)."</pre>\n";

			$metadata = array();
			$i = 0;
			foreach($columns as $column)
			{
				// for backwards compatibilty (depreciated)
				unset($flags);
				if($column->auto_increment) $flags .= "auto_increment ";
				if($column->primary_key) $flags .= "primary_key ";
				if($column->binary) $flags .= "binary ";

//				_debug_array($column);
				$metadata[$i] = array(
					'table' => $table,
					'name'  => $column->name,
					'type'  => $column->type,
					'len'   => $column->max_length,
					'flags' => $flags, // for backwards compatibilty (depreciated) used by JiNN atm
					'not_null' => $column->not_null,
					'auto_increment' => $column->auto_increment,
					'primary_key' => $column->primary_key,
					'binary' => $column->binary,
					'has_default' => $column->has_default,
					'default'  => $column->default_value,
				);
				$metadata[$i]['table'] = $table;
				if ($full)
				{
					$metadata['meta'][$column->name] = $i;
				}
				++$i;
			}
			if ($full)
			{
				$metadata['num_fields'] = $i;
			}
			return $metadata;
		}

		/**
		* Get a list of table names in the current database
		*
		* @return array list of the tables
		*/
		function table_names()
		{
			if (!$this->Link_ID) $this->connect();
			if (!$this->Link_ID)
			{
				return False;
			}
			$result = array();
			$tables = $this->Link_ID->MetaTables('TABLES');
			if (is_array($tables))
			{
				foreach($tables as $table)
				{
					if ($this->capabilities['name_case'] == 'upper')
					{
						$table = strtolower($table);
					}
					$result[] = array(
						'table_name'      => $table,
						'tablespace_name' => $this->Database,
						'database'        => $this->Database
					);
				}
			}
			return $result;
		}

		function index_names()
		{
			$indices = array();
			if ($this->Type != 'pgsql')
			{
				echo "<p>db::index_names() not yet implemented for db-type '$this->Type'</p>\n";
				return $indices;
			}
			$this->query("SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*' AND relkind ='i' ORDER BY relname");
			while ($this->nextRecord())
			{
				$indices[] = array(
					'index_name'      => $this->f(0),
					'tablespace_name' => $this->Database,
					'database'        => $this->Database,
				);
			}
			return $indices;
		}

		function pkey_columns($tablename)
		{
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			return $this->Link_ID->MetaPrimaryKeys($tablename);
		}

		function create_database($adminname = '', $adminpasswd = '', $charset='')
		{
			$currentUser = $this->User;
			$currentPassword = $this->Password;
			$currentDatabase = $this->Database;

			$extra = array();
			$set_charset = '';
			switch ($this->Type)
			{
				case 'pgsql':
					$meta_db = 'template1';
					break;
				case 'mysql':
					if ($charset && isset($this->Link_ID->charset2mysql[$charset]) && (float) $this->ServerInfo['version'] >= 4.1)
					{
						$set_charset = ' DEFAULT CHARACTER SET '.$this->Link_ID->charset2mysql[$charset].';';
					}
					$meta_db = 'mysql';
					$extra[] = "GRANT ALL ON $currentDatabase.* TO $currentUser@localhost IDENTIFIED BY '$currentPassword'";
					break;
				default:
					echo "<p>db::create_database(user='$adminname',\$pw) not yet implemented for DB-type '$this->Type'</p>\n";
					break;
			}
			if ($adminname != '')
			{
				$this->User = $adminname;
				$this->Password = $adminpasswd;
				$this->Database = $meta_db;
			}
			$this->disconnect();
			$this->query('CREATE DATABASE '.$currentDatabase.$set_charset);
			foreach($extra as $sql)
			{
				$this->query($sql);
			}
			$this->disconnect();

			$this->User = $currentUser;
			$this->Password = $currentPassword;
			$this->Database = $currentDatabase;
			$this->connect();
		}

		function concat($str1)
		{
			$args = func_get_args();

			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			return call_user_func_array(array(&$this->Link_ID,'concat'),$args);
		}

		function from_unixtime($expr)
		{
			switch($this->Type)
			{
				case 'mysql':
					return "FROM_UNIXTIME($expr)";

				case 'pgsql':	// we use date(,0) as we store server-time
					return "(timestamp '".date('Y-m-d H:i:s',0)."' + ($expr) * interval '1 sec')";

				case 'mssql':	// we use date(,0) as we store server-time
					return "DATEADD(second,($expr),'".date('Y-m-d H:i:s',0)."')";
			}
			return false;
		}

		function date_format($expr,$format)
		{
			switch($this->Type)
			{
				case 'mysql':
					return "DATE_FORMAT($expr,'$format')";

				case 'pgsql':
					$format = str_replace(
						array('%Y',  '%y','%m','%d','%H',  '%h','%i','%s','%V','%v','%X',  '%x'),
						array('YYYY','YY','MM','DD','HH24','HH','MI','SS','IW','IW','YYYY','YYYY'),
						$format);
					return "TO_CHAR($expr,'$format')";

				case 'mssql':
					$from = $to = array();
					foreach(array('%Y'=>'yyyy','%y'=>'yy','%m'=>'mm','%d'=>'dd','%H'=>'hh','%i'=>'mi','%s'=>'ss','%V'=>'wk','%v'=>'wk','%X'=>'yyyy','%x'=>'yyyy') as $f => $t)
					{
						$from[] = $f;
						$to[] = "'+DATEPART($t,($expr))+'";
					}
					$from[] = "''+"; $to[] = '';
					$from[] = "+''"; $to[] = '';
					return str_replace($from,$to,$format);
			}
			return false;
		}

		/**
		* Correctly Quote Identifiers like table- or colmnnames for use in SQL-statements
		*
		* This is mostly copy & paste from adodb's datadict class
		* @param $name string
		* @return string quoted string
		*/
		function name_quote($name = NULL)
		{
			if (!is_string($name)) {
				return FALSE;
			}

			$name = trim($name);

			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}

			$quote = $this->Link_ID->nameQuote;

			// if name is of the form `name`, quote it
			if ( preg_match('/^`(.+)`$/', $name, $matches) ) {
				return $quote . $matches[1] . $quote;
			}

			// if name contains special characters, quote it
			if ( preg_match('/\W/', $name) ) {
				return $quote . $name . $quote;
			}

			return $name;
		}

		function quote($value,$type=False,$not_null=true)
		{
			if ($this->Debug) echo "<p>db::quote(".(is_null($value)?'NULL':"'$value'").",'$type','$not_null')</p>\n";

			if (!$not_null && is_null($value))	// writing unset php-variables and those set to NULL now as SQL NULL
			{
				return 'NULL';
			}
			switch($type)
			{
				case 'int':
				case 'auto':
					return (int) $value;
				case 'bool':
					if ($this->Type == 'mysql')		// maybe it's not longer necessary with mysql5
					{
						return $value ? 1 : 0;
					}
					return $value ? 'true' : 'false';
			}
			if (!$this->Link_ID && !$this->connect())
			{
				return False;
			}
			switch($type)
			{
				case 'blob':
					switch ($this->Link_ID->blobEncodeType)
					{
						case 'C':	// eg. postgres
							return "'" . $this->Link_ID->BlobEncode($value) . "'";
						case 'I':
							return $this->Link_ID->BlobEncode($value);
					}
					break;	// handled like strings
				case 'date':
					return $this->Link_ID->DBDate($value);
				case 'timestamp':
					return $this->Link_ID->DBTimeStamp($value);
			}
			return $this->Link_ID->qstr($value);
		}

		function column_data_implode($glue,$array,$use_key=True,$only=False,$column_definitions=False)
		{
			if (!is_array($array))	// this allows to give an SQL-string for delete or update
			{
				return $array;
			}
			if (!$column_definitions)
			{
				$column_definitions = $this->column_definitions;
			}
			if ($this->Debug) echo "<p>db::column_data_implode('$glue',".print_r($array,True).",'$use_key',".print_r($only,True).",<pre>".print_r($column_definitions,True)."</pre>\n";

			$keys = $values = array();
			foreach($array as $key => $data)
			{
				if (is_int($key) || !$only || $only === True && isset($column_definitions[$key]) ||
					is_array($only) && in_array($key,$only))
				{
					$keys[] = $this->name_quote($key);

					if (!is_int($key) && is_array($column_definitions) && !isset($column_definitions[$key]))
					{
						// give a warning that we have no column-type
						$this->halt("db::column_data_implode('$glue',".print_r($array,True).",'$use_key',".print_r($only,True).",<pre>".print_r($column_definitions,True)."</pre><b>nothing known about column '$key'!</b>");
					}
					$column_type = is_array($column_definitions) && isset($column_definitions[$key]['type']) ? @$column_definitions[$key]['type'] : False;
					$not_null = is_array($column_definitions) && isset($column_definitions[$key]['nullable']) ? !$column_definitions[$key]['nullable'] : false;

					if (is_array($data))
					{
						$or_null = '';
						foreach($data as $k => $v)
						{
							if (!$not_null && $use_key===True && is_null($v))
							{
								$or_null = $this->name_quote($key).' IS NULL)';
								unset($data[$k]);
								continue;
							}
							$data[$k] = $this->quote($v,$column_type,$not_null);
						}
						$values[] = ($or_null?'(':'').(!count($data) ? '' :
							($use_key===True ? $this->name_quote($key).' IN ' : '') .
							'('.implode(',',$data).')'.($or_null ? ' OR ' : '')).$or_null;
					}
					elseif (is_int($key) && $use_key===True)
					{
						$values[] = $data;
					}
					elseif ($glue != ',' && $use_key === True && !$not_null && is_null($data))
					{
						$values[] = $this->name_quote($key) .' IS NULL';
					}
					else
					{
						$values[] = ($use_key===True ? $this->name_quote($key) . '=' : '') . $this->quote($data,$column_type,$not_null);
					}
				}
			}
			if(count($keys) == 0) return '';
			return ($use_key==='VALUES' ? '('.implode(',',$keys).') VALUES (' : '').
				implode($glue,$values) . ($use_key==='VALUES' ? ')' : '');
		}

		function set_column_definitions($column_definitions=False)
		{
			$this->column_definitions=$column_definitions;
		}

		function set_app($app)
		{
			$this->app = $app;
		}

		function get_table_definitions($app=False,$table=False)
		{
			if ($app === true && $table && isset($GLOBALS['plus_info']['apps']))
			{
				foreach($GLOBALS['plus_info']['apps'] as $app => $app_data)
				{
					if (isset($data['table_defs'][$table]))
					{
						return $data['table_defs'][$table];
					}
				}
				$app = false;
			}
			if (!$app)
			{
				$app = $this->app ? $this->app : $GLOBALS['app']->route->app;// app app
			}
			if (isset($GLOBALS['plus_info']['apps']))	// dont set it, if it does not exist!!!
			{
				$this->app_data = &$GLOBALS['plus_info']['apps'][$app];
			}
			// this happens during the eGW startup or in setup
			else
			{
				$this->app_data =& $this->all_app_data[$app];
			}
			if (!isset($this->app_data['table_defs']))
			{
				$tables_current = BETHLEHEM_ROOT_DIR . "/$app/setup/tables_current.inc.php";
				if (!@file_exists($tables_current))
				{
					return $this->app_data['table_defs'] = False;
				}
				include($tables_current);
				$this->app_data['table_defs'] =& $phpgw_baseline;
				unset($phpgw_baseline);
			}
			if ($table && (!$this->app_data['table_defs'] || !isset($this->app_data['table_defs'][$table])))
			{
				return False;
			}
			return $table ? $this->app_data['table_defs'][$table] : $this->app_data['table_defs'];
		}

		function insert($table,$data,$where,$line,$file,$app=False,$use_prepared_statement=false,$table_def=False)
		{
			if ($this->Debug) echo "<p>db::insert('$table',".print_r($data,True).",".print_r($where,True).",$line,$file,'$app')</p>\n";

			if (!$table_def) $table_def = $this->get_table_definitions($app,$table);

			$sql_append = '';
			$cmd = 'INSERT';
			if (is_array($where) && count($where))
			{
				switch($this->Type)
				{
					case 'sapdb': case 'maxdb':
						$sql_append = ' UPDATE DUPLICATES';
						break;
					case 'mysql':
						// use replace if primary keys are included
						if (count(array_intersect(array_keys($where),(array)$table_def['pk'])) == count($table_def['pk']))
						{
							$cmd = 'REPLACE';
							break;
						}
						// fall through !!!
					default:
						$this->select($table,'count(*)',$where,$line,$file);
						if ($this->nextRecord() && $this->f(0))
						{
							return !!$this->update($table,$data,$where,$line,$file,$app);
						}
						break;
				}
				// the checked values need to be inserted too, value in data has precedence, also cant insert sql strings (numerical id)
				foreach($where as $column => $value)
				{
					if (!is_numeric($column) && !isset($data[$column]))
					{
						$data[$column] = $value;
					}
				}
			}

			$inputarr = false;
			if ($use_prepared_statement && $this->Link_ID->_bindInputArray)	// eg. MaxDB
			{
				$this->Link_ID->Param(false);	// reset param-counter
				$cols = array_keys($data);
				foreach($cols as $k => $col)
				{
					if (!isset($table_def['fd'][$col]))	// ignore columns not in this table
					{
						unset($cols[$k]);
						continue;
					}
					$params[] = $this->Link_ID->Param($col);
				}
				$sql = "$cmd INTO $table (".implode(',',$cols).') VALUES ('.implode(',',$params).')'.$sql_append;
				// check if we already prepared that statement
				if (!isset($this->prepared_sql[$sql]))
				{
					$this->prepared_sql[$sql] = $this->Link_ID->Prepare($sql);
				}
				$sql = $this->prepared_sql[$sql];
				$inputarr = &$data;
			}
			else
			{
				//$sql = "$cmd INTO $table ".$this->column_data_implode(',',$data,'VALUES',true,$table_def['fd']).$sql_append;
				$insert_data = $this->column_data_implode(',',$data,'VALUES',true,$table_def['fd']);
				$sql = ($insert_data != '') ? "$cmd INTO $table ".$insert_data.$sql_append : '';				
			}
			if ($this->Debug) echo "<p>db::insert('$table',".print_r($data,True).",".print_r($where,True).",$line,$file,'$app') sql='$sql'</p>\n";
			return $this->query($sql,$line,$file,0,-1,$inputarr);
		}

		function update($table,$data,$where,$line,$file,$app=False,$use_prepared_statement=false,$table_def=False)
		{
			if ($this->Debug) echo "<p>db::update('$table',".print_r($data,true).','.print_r($where,true).",$line,$file,'$app')</p>\n";
			if (!$table_def) $table_def = $this->get_table_definitions($app,$table);

			$blobs2update = array();
			// SapDB/MaxDB cant update LONG columns / blob's: if a blob-column is included in the update we remember it in $blobs2update
			// and remove it from $data
			switch ($this->Type)
			{
				case 'sapdb':
				case 'maxdb':
					if ($use_prepared_statement) break;
					// check if data contains any LONG columns
					foreach($data as $col => $val)
					{
						switch ($table_def['fd'][$col]['type'])
						{
							case 'text':
							case 'longtext':
							case 'blob':
								$blobs2update[$col] = &$data[$col];
								unset($data[$col]);
								break;
						}
					}
					break;
			}
			$where = $this->column_data_implode(' AND ',$where,True,true,$table_def['fd']);

			if (count($data))
			{
				$inputarr = false;
				if ($use_prepared_statement && $this->Link_ID->_bindInputArray)	// eg. MaxDB
				{
					$this->Link_ID->Param(false);	// reset param-counter
					foreach($data as $col => $val)
					{
						if (!isset($table_def['fd'][$col])) continue;	// ignore columns not in this table
						$params[] = $this->name_quote($col).'='.$this->Link_ID->Param($col);
					}
					$sql = "UPDATE $table SET ".implode(',',$params).' WHERE '.$where;
					// check if we already prepared that statement
					if (!isset($this->prepared_sql[$sql]))
					{
						$this->prepared_sql[$sql] = $this->Link_ID->Prepare($sql);
					}
					$sql = $this->prepared_sql[$sql];
					$inputarr = &$data;
				}
				else
				{
					//$sql = "UPDATE $table SET ".
						//$this->column_data_implode(',',$data,True,true,$table_def['fd']).' WHERE '.$where;
					$sql = "UPDATE $table";
					if($set_data = $this->column_data_implode(',',$data,True,true,$table_def['fd'])) $sql .= ' SET ' . $set_data;
					if($where) $sql .= ' WHERE ' . $where;
					if($sql == "UPDATE $table") $sql = '';
				}
				
				$ret = $this->query($sql,$line,$file,0,-1,$inputarr);
				if ($this->Debug) echo "<p>db::query('$sql',$line,$file) = '$ret'</p>\n";
			}

			// if we have any blobs to update, we do so now
			if (($ret || !count($data)) && count($blobs2update))
			{
				foreach($blobs2update as $col => $val)
				{
					$ret = $this->Link_ID->UpdateBlob($table,$col,$val,$where,$table_def['fd'][$col]['type'] == 'blob' ? 'BLOB' : 'CLOB');
					if ($this->Debug) echo "<p>adodb::UpdateBlob('$table','$col','$val','$where') = '$ret'</p>\n";
					if (!$ret) $this->halt("Error in UpdateBlob($table,$col,\$val,$where)",$line,$file);
				}
			}
			return $ret;
		}

		function delete($table,$where,$line='',$file='',$app=False,$table_def=False)
		{
			if (!$table_def) $table_def = $this->get_table_definitions($app,$table);
			$sql = "DELETE FROM $table WHERE ".
				$this->column_data_implode(' AND ',$where,True,False,$table_def['fd']);

			return $this->query($sql,$line,$file);
		}

		function expression($table_def,$args)
		{
			if (!is_array($table_def)) $table_def = $this->get_table_definitions('',$table_def);
			$sql = '';
			$ignore_next = 0;
			foreach(func_get_args() as $n => $arg)
			{
				if ($n < 1) continue;	// table-name

				if ($ignore_next)
				{
					--$ignore_next;
					continue;
				}
				if (is_null($arg)) $arg = False;

				switch(gettype($arg))
				{
					case 'string':
						$sql .= $arg;
						break;
					case 'boolean':
						$ignore_next += !$arg ? 2 : 0;
						break;
					case 'array':
						$sql .= $this->column_data_implode(' AND ',$arg,True,False,$table_def['fd']);
						break;
				}
			}
			if ($this->Debug) echo "<p>db::expression($table,<pre>".print_r(func_get_args(),True)."</pre>) ='$sql'</p>\n";
			return $sql;
		}

		function select($table,$cols,$where,$line,$file,$offset=False,$append='',$app=False,$num_rows=0,$join='',$table_def=False)
		{

			if ($this->Debug) echo "<p>db::select('$table',".print_r($cols,True).",".print_r($where,True).",$line,$file,$offset,'$app',$num_rows,'$join')</p>\n";
			
			if (!$table_def) $table_def = $this->get_table_definitions($app,$table);
			if (is_array($cols)) {
				$cols = implode(',',$cols);
			}

			if (is_array($where))
			{
				$where = $this->column_data_implode(' AND ',$where,True,False,$table_def['fd']);
			}
			$sql = "SELECT $cols FROM $table $join";

			// if we have a where clause, we need to add it together with the WHERE statement, if thats not in the join
			if ($where) $sql .= strstr($join,"WHERE") ? ' AND ('.$where.')' : ' WHERE '.$where;

			if ($append) $sql .= ' '.$append;

			if ($this->Debug) echo "<p>sql='$sql'</p>";

			if ($line === false && $file === false)	// call by union, to return the sql rather then run the query
			{
				return $sql;
			}

			return $this->query($sql,$line,$file,$offset,$offset===False ? -1 : (int)$num_rows);
		}

		function union($selects,$line,$file,$order_by='',$offset=false,$num_rows=0)
		{
			if ($this->Debug) echo "<p>db::union(".print_r($selects,True).",$line,$file,$order_by,$offset,$num_rows)</p>\n";

			$sql = array();
			foreach($selects as $select)
			{
				$sql[] = call_user_func_array(array($this,'select'),array(
					$select['table'],
					$select['cols'],
					$select['where'],
					false,	// line
					false,	// file
					false,	// offset
					$select['append'],
					$select['app'],
					0,		// num_rows,
					$select['join'],
					$select['table_def'],
				));
			}
			$sql = count($sql) > 1 ? '(' . implode(")\nUNION\n(",$sql).')' : $sql[0];

			if ($order_by) $sql .=  (!stristr($order_by,'ORDER BY') ? "\nORDER BY " : '').$order_by;

			if ($this->Debug) echo "<p>sql='$sql'</p>";

			return $this->query($sql,$line,$file,$offset,$offset===False ? -1 : (int)$num_rows);
		}
	}
