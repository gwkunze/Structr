<?php
/**
 * Copyright (c) 2012 Gijs Kunze
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Structr\Tree\Composite;

use Structr\Tree\Base\Node;

use Structr\Exception;

class MapNode extends Node
{
    /**
     * @var array Keys currently registered for this map
     */
    private $_keys = array();
    
    /**
     * @var string A regular expression to match certain keys
     */
    private $_regexp_keys = array();
    
    /**
     * @var array A callable to filter keys
     */
    private $_callable_keys = array();
    
    /**
     * @var boolean Whether this map should be strict. A strict map throws an
     *      exception when there are keys it didn't expect.
     */
    private $_strict = false;

    /**
     * Add a key to this map
     * 
     * @param string $keyname Name of the key
     * @return \Structr\Tree\Composite\MapKeyNode
     */
    public function key($keyname)
    {
        $this->_keys[$keyname] = new MapKeyNode($this);
        $this->_keys[$keyname]->setName($keyname);

        return $this->_keys[$keyname];
    }

    /**
     * Add a key-matcher to this map. Either via a callable or a
     * regular expression
     * 
     * @param string|mixed $matcher The filter
     * @param string $name Name for the key
     * @return \Structr\Tree\Composite\MapKeyNode
     */
    public function keyMatch($matcher, $name = null)
    {
        if (is_callable($matcher, false, $callable_name)) {
            if ($name === null) {
                $name = $callable_name;
            }
            $node = new MapKeyNode($this);
            $node->setName($name);
            $this->_callable_keys[] = array(
                'callable' => $matcher,
                'node' => $node,
            );
            return $node;
        } else {
            if ($name === null) {
                $name = $matcher;
            }
            $node = new MapKeyNode($this);
            $node->setName($name);
            $this->_regexp_keys[$matcher] = $node;

            return $node;
        }
    }

    /**
     * Remove a key from this map
     * 
     * @param string $keyName The name of the key to remove
     * @return \Structr\Tree\Composite\MapNode This node
     */
    public function removeKey($keyName)
    {
        if (isset($this->_keys[$keyName])) {
            unset($this->_keys[$keyName]);
        }

        return $this;
    }

    /**
     * Reset this map. Forgetting the current state completely.
     * 
     * @return \Structr\Tree\Composite\MapNode This node
     */
    public function clear()
    {
        $this->_strict = false;
        $this->_keys = array();
        $this->_regexp_keys = array();
        $this->_callable_keys = array();

        return $this;
    }

    /**
     * Call when this map must be strict, i.e., throw an exception when there
     * are keys in the input that are not described by this map
     * 
     * @return \Structr\Tree\Composite\MapNode This node
     */
    public function strict()
    {
        $this->_strict = true;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function _walk_value($value)
    {
        $value = parent::_walk_value($value);

        if (!is_array($value)) {
            throw new Exception(sprintf(
                "Invalid type '%s', expecting 'map' (associative array)",
                gettype($value)
            ));
        }

        $return = array();

        foreach ($this->_keys as $key => $val) {
            if (isset($value[$key])) {
                $return[$key] = $val->_walk($value[$key]);
            } elseif ($val->isOptional()) {
                continue;
            } else {
                $return[$key] = $val->_walk_post($val->_walk_value_unset());
            }
            unset($value[$key]);
        }

        foreach ($this->_regexp_keys as $regexp => $val) {
            foreach (array_keys($value) as $key) {
                if (preg_match($regexp, $key)) {
                    $return[$key] = $val->_walk($value[$key]);
                    unset($value[$key]);
                }
            }
        }

        foreach ($this->_callable_keys as $callable) {
            foreach (array_keys($value) as $key) {
                if (call_user_func($callable['callable'], $key)) {
                    $return[$key] = $callable['node']->_walk($value[$key]);
                    unset($value[$key]);
                }
            }
        }

        if ($this->_strict && count($value)) {
            throw new Exception(sprintf(
                'Unexpected key(s) %s',
                implode(', ', array_keys($value))
            ));
        }
        
        return $return;
    }
}
