<?php

/**
 * Classes that are part of the QueryPath module.
 *
 * @file
 */
 
/**
 * Add Drupal database support to QueryPath.
 *
 * This is a Drupal-specific port of QPDB, the database extension that 
 * is bundled with QueryPath. For detailed documentation, see 
 * {@link http://querypath.org} for details.
 */
class QPDrupalDB implements QueryPathExtension {
  /**
   * QueryPath instance for this extension.
   */
  protected $qp;
  
  protected $results = NULL;
  protected $row = NULL;
  protected $cycleRows = FALSE;
  
  /**
   * Construct a new extension instance. 
   * There is no need to call this directly.
   */
  public function __construct(QueryPath $qp) {
    $this->qp = $qp;
  }
  
  public function query($sql) {
    $args = func_get_args();
    array_shift($args);
    
    // If first item is an array, set args to that array.
    if (!empty($args) && is_array($args[0])) {
      $args = $args[0];
    }
    $this->results = db_query($sql, $args);
    return $this->qp;
  }
  
  /**
   * Get the raw SQL results.
   */
  public function retrieveResults() {
    return $this->results;
  }
  
  /**
   * This needs some fixing up to be as grand as QPDB's version.
   */
  public function queryInto($sql) {
    $args = func_get_args();
    array_shift($args);
    
    // If first item is an array, set args to that array.
    if (!empty($args) && is_array($args[0])) {
      $args = $args[0];
    }
    $results = db_query($sql, $args);
    
    foreach ($result as $row) {
      $rows[] = $row;
      foreach ($rows as $datum) {
        $this->qp->append($datum);
      }
    }
    
    return $this->qp;
  }
  
  /**
   * Fetch the next row from the current result set.
   */
  public function nextRow() {
    $this->row = db_fetch_array($this->result);
    return $this->qp;
  }
  
  /**
   * Repeat for all found rows.
   */
  public function withEachRow() {
    $this->cycleRows = TRUE;
    return $this->qp;
  }
  
  /**
   * Clear the stored query and free up resources.
   *
   * You should always use this when you care done with a query -- especially
   * if you intend to perform additional queries.
   */
  public function doneWithQuery() {
    $this->cycleRows = FALSE;
    $this->row = NULL;
    $this->results = NULL;
  }
  
  // Straight copy from QPDB:
  protected function addData($columnName, $qpFunc = 'append', $wrap = NULL) {
    $columns = is_array($columnName) ? $columnName : array($columnName);
    $hasWrap = !empty($wrap);
    if ($this->cycleRows) {
      foreach ($this->results as $record) {
        foreach ($columns as $col) {
          if (isset($record->$col)) {
            $data = $record->$col;
            if ($hasWrap) 
              $data = qp($wrap)->deepest()->append($data)->top();
            $this->qp->$qpFunc($data);
          }
        }
      }
      $this->cycleRows = FALSE;
      $this->doneWithQuery();
    }
    else {
      if ($this->row !== FALSE) {
        foreach ($columns as $col) {
          if (isset($this->row[$col])) {
            $data = $this->row[$col];
            if ($hasWrap) 
              $data = qp($wrap)->deepest()->append($data)->top();
            $this->qp->$qpFunc($data);
          }
        }
      }
    }
    return $this->qp;
  }
  
  // From here, we just copy straight out of QPDB
  
  /**
   * Append the data in the given column(s) to the QueryPath.
   *
   * This appends data to every item in the current QueryPath. The data will
   * be retrieved from the database result, using $columnName as the key.
   *
   * @param mixed $columnName
   *  Either a string or an array of strings. The value(s) here should match 
   *  one or more column headers from the current SQL {@link query}'s results.
   * @param string $wrap
   *  IF this is supplied, then the value or values retrieved from the database
   *  will be wrapped in this HTML/XML before being inserted into the QueryPath.
   * @see QueryPath::wrap()
   * @see QueryPath::append()
   */
  public function appendColumn($columnName, $wrap = NULL) {
    return $this->addData($columnName, 'append', $wrap); 
  }
  
  /**
   * Prepend the data from the given column into the QueryPath.
   *
   * This takes the data from the given column(s) and inserts it into each
   * element currently found in the QueryPath.
   * @param mixed $columnName
   *  Either a string or an array of strings. The value(s) here should match 
   *  one or more column headers from the current SQL {@link query}'s results.
   * @param string $wrap
   *  IF this is supplied, then the value or values retrieved from the database
   *  will be wrapped in this HTML/XML before being inserted into the QueryPath.
   * @see QueryPath::wrap()
   * @see QueryPath::prepend()
   */
  public function prependColumn($columnName, $wrap = NULL) {
    return $this->addData($columnName, 'prepend', $wrap);
  }
  
  /**
   * Insert the data from the given column before each element in the QueryPath.
   *
   * This inserts the data before each element in the currently matched QueryPath.
   *
   * @param mixed $columnName
   *  Either a string or an array of strings. The value(s) here should match 
   *  one or more column headers from the current SQL {@link query}'s results.
   * @param string $wrap
   *  IF this is supplied, then the value or values retrieved from the database
   *  will be wrapped in this HTML/XML before being inserted into the QueryPath.
   * @see QueryPath::wrap()
   * @see QueryPath::before()
   * @see prependColumn()
   */
  public function columnBefore($columnName, $wrap = NULL) {
    return $this->addData($columnName, 'before', $wrap);
  }
  
  /**
   * Insert data from the given column(s) after each element in the QueryPath.
   *
   * This inserts data from the given columns after each element in the QueryPath
   * object. IF HTML/XML is given in the $wrap parameter, then the column data
   * will be wrapped in that markup before being inserted into the QueryPath.
   *
   * @param mixed $columnName
   *  Either a string or an array of strings. The value(s) here should match 
   *  one or more column headers from the current SQL {@link query}'s results.
   * @param string $wrap
   *  IF this is supplied, then the value or values retrieved from the database
   *  will be wrapped in this HTML/XML before being inserted into the QueryPath.
   * @see QueryPath::wrap()
   * @see QueryPath::after()
   * @see appendColumn()
   */
  public function columnAfter($columnName, $wrap = NULL) {
    return $this->addData($columnName, 'after', $wrap);
  }
}
// Register this as an extension.
QueryPathExtensionRegistry::extend('QPDrupalDB');