<?php

namespace Support\Utility;

class Session
{
    public function start()
    {
        return session_start();
    }

    public function regenerate()
    {
        return session_regenerate_id(true);
    }

    public function exists($name)
    {
        return isset($_SESSION[$name]);
    }

    public function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public function get($name, $default = null)
    {
	    if ($this->exists($name)) {
            return $_SESSION[$name];
	    }
	    return $default;
    }

    public function delete($name)
    {
        if ($this->exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    

    public function all()
    {
        return $_SESSION;
    }
}
?>