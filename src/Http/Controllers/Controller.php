<?php

namespace Ruysu\Core\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;

abstract class Controller extends LaravelController
{

    use DispatchesJobs, ValidatesRequests;

    /**
     * Redirect to a url, or simply return the redirect response
     * @param  string|null  $action
     * @param  array        $params
     * @param  integer      $status
     * @return Redirect
     */
    protected function redirect($action = null, array $params = array(), $status = 302)
    {
        $redirect = redirect();

        if ($action) {
            $redirect = $redirect->to($this->url($action, $params), $status);
        }

        return $redirect;
    }

    /**
     * Compute a url to an action
     * @param  string $action
     * @param  array  $params
     * @return string
     */
    protected function url($action, array $params = array())
    {
        $to = $this->action($action);

        if (strpos($to, '@') === false) {
            return url($to);
        }

        return action('\\' . $to, $params);
    }

    /**
     * If any \ are present, just return the string as is. If no \ are, but @ is
     * present, takes the current namespace and adds the given controller name.
     * If \ nor @ are present, takes the current controller class name and
     * appends the given action.
     * @param  string $action
     * @return string fully namespaced Controller@Action
     */
    protected function action($action)
    {
        static $classname;

        if ($classname === null) {
            $classname = get_class($this);
        }

        if (strpos($action, '@') === false) {
            if (method_exists($this, $action)) {
                return $classname . '@' . $action;
            }
        } elseif (strpos($action, '\\') === false) {
            $namespace = substr($classname, 0, strrpos($classname, '\\'));

            if (substr($action, 0, 1) === '@') {
                return $classname . $action;
            }

            if (!empty($namespace)) {
                return $namespace . '\\' . $action;
            }
        }

        return $action;
    }

}
