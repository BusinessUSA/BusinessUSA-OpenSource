<?php

/**
 * qpa.phpunit
 *
 * @file
 */
 
$test = array(
  'top1' => array('inner1' => 'value1', 'inner2' => 'value2'),
  'top2' => array('inner3' => 'value3', 'inner4' => 'value4'),
  'top3' => array('inner1' => 'value5', 'inner6' => 'value6'),
  'top4' => array('middle1' => array('middle2' => array('inner1' => 'value7', 'inner8' => 'value8'))),
);

$f = new D($test);
$f->find('inner1');

print 'Matches: ' . PHP_EOL;
var_dump($f->matches);

print "First match:\n";
print $f->getVal($f->matches[0]);

print "\nSecond match:\n";
print $f->getVal($f->matches[1]);

print "\nThird match:\n";
print $f->getVal($f->matches[2]);

print "\nSet a value:\n";
$f->setVal($f->matches[2], 'FOOOOO');
print $f->getVal($f->matches[2]);

print PHP_EOL . 'Array: ' . PHP_EOL;
var_dump($test);


class D {
  
  public $arr = NULL;
  public $matches = NULL;
  
  function __construct(&$arr) {
    $this->arr =& $arr;
    $this->matches = array($arr);
  }
  
  function find($name) {
    $this->matches = $this->descendList($name);
  }
  
  /**
   * Get the value of an item in a deeply nested array.
   */
  function getVal($key) {
    $t = $this->arr;
    foreach ($key as $part) {
      $t = $t[$part];
    }
    return $t;
  }
  
  /**
   * Set the value of an item in a deeply nested array.
   */
  function setVal($key, $value) {
    $t =& $this->arr;
    $c = count($key);
    for ($i = 0; $i < $c; ++$i) {
      $part = $key[$i];
      
      if ($c - 1 == $i) {
        $t[$part] = $value;
      }
      else {
        $t =& $t[$part];
      }
    }
    return $t;
    
  }
  
  /**
   * Descend through a list and find matches.
   *
   * @param $name
   *   Name of item to match.
   * @param $value
   *   An optional value that (if supplied) also must be matched before a found 
   *   item is returned as a match.
   * @param $list
   *   List of items to search. This should be any traversable that is numerically
   *   indexed. The assumption is that at least some of this list's items will be
   *   associative arrays.
   * @return $matches
   *   Returns the list of items that matched. Note that for any two items in matches, 
   *   one item may actually be a child of the other item.
   */
  protected function descendList($name, $value = NULL, $list = NULL) {
    if (!isset($list)) {
      $list = $this->matches;
    }
    
    $matches = array();
    foreach ($list as $li) {
      $this->descender($name, $li, $value, $matches);
    }
    return $matches;
  }
  
  /**
   * Search a nested array.
   *
   * This will recurse through n-deep arrays, storing a collection of matches.
   *
   * @param $name
   *  String name of the item to search for. If searching for an atribute, prepend
   *  this with '#'.
   * @param $items
   *  An associative array of items to seek.
   * @param $value
   *  An (optional) value to search for. If this is specified, both name and value
   *  must be matched before an item is considered a match.
   * @param $matches
   *  An array of matches. This is typically only used when recursing. Don't use
   *  it unless you know what you are doing.
   */
  protected function descender($name, $items, $value = NULL, &$matches = array(), $prefix = array()) {
    // XXX: this could be expanded to handle traversables.
    if (!is_array($items)) {
      return $matches;
    }
    
    foreach ($items as $n => $v) {
      if ($n == $name) {
        
        // If value is set, then we do a comparison
        if (isset($value)) {
          // If the comparison matches, add the item to matches.
          if ($value == $v) {
            $key = $prefix;
            $key[] = $name;
            $matches[] = $key;
          }
        }
        // If the value is not set, we consider it a match.
        else {
          $key = $prefix;
          $key[] = $name;
          $matches[] = $key;
        }
      }
        
      if (is_array($v)) {
        // Recurse
        $base = $prefix;
        $base[] = $n;
        $this->descender($name, $v, $value, $matches, $base);
      }
    }
    
    return $matches;
  }
}
