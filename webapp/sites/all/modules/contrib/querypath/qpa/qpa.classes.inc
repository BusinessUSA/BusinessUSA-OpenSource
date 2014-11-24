<?php

/**
 * qpa.classes
 *
 * @file
 */


class QPACssEventHandler implements CssEventHandler {
  protected $form = NULL;
  protected $matches = NULL; // The matches
  protected $alreadyMatched = NULL; // Matches found before current selector.
  protected $findAnyElement = TRUE;
  
  public function __construct($form) {
    if (!is_array($form)) {
      throw new Exception('Expected form in constructor.');
    }
    
    // This is the form array
    $this->form = $form;
    
    // Matches is a numerically indexed array
    $this->matches[] = $form;
  }
  
  public function find($selector) {
    $parser = new CssParser($selector, $this);
    $parser->parse();
    return $this;
  }
  
  public function getMatches() {
    $result = array_merge($this->alreadyMatched, $this->matches);
    return $result;
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
   *  this with '#'. If name = '*', any element name that does not begin with '#'
   *  will be matched.
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
      if ($n == $name || ($name == '*' && $n[0] != '#')) {
        
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
  
  public function element($name) {
    $this->matches = $this->descendList($name);
  }
  
  public function attribute($name) {
    $this->matches = $this->descendList('#' . $name);
  }
  
  public function elementID($id) {
    throw new Exception('FAPI arrays do not have IDs.');
  }
  
  public function anyElement() {
    $this->matches = $this->descendList('*');
  }
  
  public function pseudoClass($name, $value = NULL) {
    
  }

  public function pseudoElement($name) {
    throw new Exception('No pseudo-elements are supported.');
  }
  
  public function directDescendant() {
    $found = array();
    // For each match...
    foreach ($this->matches as $m) {
      // If it has children that are arrays...
      $val = $this->getVal($m);
      if (is_array($val)) {
        // Go through each child...
        foreach ($val as $k => $v) {
          // And add any elements found to the $found array.
          if ($k[0] != '#') {
            $found[] = array($m, $k);
          }
        }
      }
    }
    $this->matches = $found;
  }
  
  public function adjacent() {
    
  }
  
  public function anotherSelector() {
    
  }
  
  public function sibling() {
    
  }
  
  public function anyDescendant() {
    
  }
  
  /////////////////
  // UNSUPPORTED //
  /////////////////
  
  
  public function elementNS($id) {
    throw new Exception('Namespaces are not supported in arrays.');
  }
  
  
  
  public function anyElementInNS($ns) {
    throw new Exception('Namespaces are not supported in arrays.');
  }
  
  public function attributeNS($name, $ns, $v=NULL, $o=NULL) {
    throw new Exception('Namespaces are not supported in arrays.');
  }
}
