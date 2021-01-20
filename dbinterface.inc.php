<?php

include 'config.db.php';

	class DB {
		
		private static $sqli=null;
		
		/*
		 * Create a connection to the database server.
		 */
		static function connect() {
			if (isset(DB::$sqli)) die ("db reconnect");
			DB::$sqli= new mysqli(DBConfig::$host,DBConfig::$user,DBConfig::$pwd,DBConfig::$database);
			if (!isset(DB::$sqli)) die ("connect: no sqli");
			DB::$sqli->set_charset('utf8');
			return DB::$sqli;
		}
		
		/*
		 * Executes a query and returns a single value.
		 */
		static function get_value($query) {
			if (!isset(DB::$sqli)) die ("get_value ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) die ("get_value ($query): ".DB::$sqli->error);
			if ($row = $res->fetch_row()) {
				$res->free();
				return $row[0];
			} else {
				die ("get_value ($query): ".DB::$sqli->error);
			}
		}
		

		/*
		 * Executes a query and returns a single value if present, otherwise false.
		 */		
		static function get_value_or_false($query) {
			if (!isset(DB::$sqli)) die ("get_value_or_false ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) return false;
			if ($res->num_rows==0) return false;
			if ($row = $res->fetch_row()) {
				$res->free();
				return $row[0];
			} else {
				die ("get_value_or_false ($query): ".DB::$sqli->error);
			}
		}

		/*
		 * Executes a query and returns all entries of the first column from the result set indexed array.
		 */
		static function get_list($query) {
			if (!isset(DB::$sqli)) die ("get_list ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) die ("get_list ($query): ".DB::$sqli->error);
			$data=array();
			while ($row = $res->fetch_row()) {
				$data[]=$row[0];
			}
			$res->free();
			return $data;
		}

		/*
		 * Executes a query and returns the complete result as array of associative arrays.
		 */
		static function get_assoc($query) {
			if (!isset(DB::$sqli)) die ("get_assoc ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) die ("get_assoc ($query): ".DB::$sqli->error);
			$data=array();
			while ($row = $res->fetch_assoc()) {
				$data[]=$row;
			}
			$res->free();
			return $data;
		}

		/*
		 * Executes a query and returns the first row of the result as associative array.
		 */
		static function get_assoc_row($query) {
			if (!isset(DB::$sqli)) die ("get_assoc_row ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) die ("get_assoc ($query): ".DB::$sqli->error);
			if ($row = $res->fetch_assoc()) {
				$res->free();
				return $row;
			} else {
				die ("get_assoc_row ($query): ".DB::$sqli->error);
			}
		}
		

		/*
		 * Returns true if a query returns exactly one row.
		 */		
		static function check($query) {
			if (!isset(DB::$sqli)) die ("check ($query): no sqli");
			$res=DB::$sqli->query($query);
			if (!$res) return false;
			return true;
		}

		/*
		 * Execute a query (use with INSERT, UPDATE...)
		 */		
		static function query($query) {
			DB::log('DBIF',$query);
			if (!isset(DB::$sqli)) die ("DB::query ($query): no sqli");
			DB::$sqli->query($query);
			if (DB::$sqli->error!='') die ("DB::query ($query): ".DB::$sqli->error);
		}
		
		/*
		 * Returns the SQL interface instance.
		 */
		static function sqli() {
			return DB::$sqli;
		}
		
		/*
		 * Escape a string.
		 */
		static function esc($str) {
			return DB::$sqli->real_escape_string($str);
		}
		
		/*
		 * Check whether a string is affected by escaping.
		 */
		static function test($data) {
			if (DB::esc($data)!=$data) die ("input error");
		}

		/*
		 * Append an entry to the database log file.
		 */
		static function log($tag,$data) {
			$fp = fopen('db.log', 'a');
			fwrite($fp, date("Y-m-d H:i:s").': '.$tag.': '.$data."\n");
			fclose($fp);
		}
		
		static function close() {
			mysqli_close(DB::$sqli);
		}
		
	}
	
?>