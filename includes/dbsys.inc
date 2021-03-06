<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

// $Id: dbsys.inc 1011 2009-02-06 12:47:24Z jberanek $

global $dbsys;

// Include the abstraction configured to be used for the default MRBS
// database
require_once("${dbsys}.inc");


// All the sql_* functions below apart from sql_connect() and
// sql_default_connect() take an optional final argument which is
// the MRBS database handle to use. If this is not passed, the default
// MRBS database connection is used.


// Free a result object
function sql_free($r)
{
  global $dbsys;

  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_free";
  $f($r, $db_conn);
}


// Run an SQL query that doesn't produce results
function sql_command($sql)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_command";
  return $f($sql, $db_conn);
}


// Run an SQL query that returns only one result - returns the result
// directly. SQL query must select only one column and one row.
function sql_query1($sql)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_query1";
  return $f($sql, $db_conn);
}


// Run a SQL query, returns a result object
function sql_query($sql)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_query";
  return $f($sql, $db_conn);
}


// Return one row from a result object, as a numbered array
function sql_row($r, $i)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_row";
  return $f($r, $i, $db_conn);
}


// Return one row from a result object, as an array keyed on the column
// name
function sql_row_keyed($r, $i)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_row_keyed";
  return $f($r, $i, $db_conn);
}


// Return the number of rows in a result object
function sql_count($r)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_count";
  return $f($r, $db_conn);
}


// Get the last ID inserted into a table
function sql_insert_id($table, $field)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_insert_id";
  return $f($table, $field, $db_conn);
}


// Return the last SQL error for a connection
function sql_error()
{
  if (func_num_args() > 0)
  {
    $handle = func_get_arg(0);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_error";
  return $f();
}


// Start a transaction
function sql_begin()
{
  if (func_num_args() > 0)
  {
    $handle = func_get_arg(0);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_begin";
  $f($db_conn);
}


// Finish a transaction
function sql_commit()
{
  if (func_num_args() > 0)
  {
    $handle = func_get_arg(0);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_commit";
  $f($db_conn);
}


// Get a mutex lock
function sql_mutex_lock($name)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_mutex_lock";
  return $f($name, $db_conn);
}


// Return a mutex lock
function sql_mutex_unlock($name)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_mutex_unlock";
  $f($name, $db_conn);
}


// Return the SQL database version for the connection
function sql_version()
{
  if (func_num_args() > 0)
  {
    $handle = func_get_arg(0);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_version";
  return $f($db_conn);
}


// Returns the syntax for a limit statement
function sql_syntax_limit($count, $offset)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_syntax_limit";
  return $f($count, $offset, $db_conn);
}


// Returns the syntax for converting a timestamp into a "UNIX timestamp"
function sql_syntax_timestamp_to_unix($fieldname)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_syntax_timestamp_to_unix";
  return $f($fieldname, $db_conn);
}


// Returns the syntax for a caseless "contains" function
function sql_syntax_caseless_contains($fieldname, $s)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_syntax_caseless_contains";
  return $f($fieldname, $s, $db_conn);
}


// Return the name of a column in a result object
function sql_field_name($result, $index)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_field_name";
  return $f($result, $index, $db_conn);
}


// Return the type of a column in a result object
function sql_field_type($result, $index)
{
  if (func_num_args() > 2)
  {
    $handle = func_get_arg(2);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_field_type";
  return $f($result, $index, $db_conn);
}


// Return the number of columns in a result object
function sql_num_fields($result)
{
  if (func_num_args() > 1)
  {
    $handle = func_get_arg(1);
    $db_sys = $handle['system'];
    $db_conn = $handle['connection'];
  }
  else
  {
    global $dbsys;

    $db_sys = $dbsys;
    $db_conn = null;
  }

  $f = "sql_${db_sys}_num_fields";
  return $f($result, $db_conn);
}


// Connect to a database host and select the supplied database
function sql_connect($system, $host, $username, $password, $db_name,
                     $persist = 0)
{
  require_once "$system.inc";

  $f = "sql_${system}_connect";

  $db_conn = $f($host, $username, $password, $db_name, $persist);

  $handle = array('system' => $system, 'connection' => $db_conn);

  return $handle;
}


//////////////////////////////////////////
// Connect to the configured MRBS database

$f = "sql_${dbsys}_default_connect";
$f();
