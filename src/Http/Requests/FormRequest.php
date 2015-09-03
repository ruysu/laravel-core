<?php

namespace Ruysu\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Routing\Router;

abstract class FormRequest extends LaravelFormRequest
{

    /**
     * Whether specific rules are merged with rulesForAll or not.
     * @var boolean
     */
    protected $merge = true;

    /**
     * Variables to replace in the validation rules.
     * @var array
     */
    protected $replace = array(
        'key' => 'NULL',
    );

    /**
     * Determine if the request passes the authorization check.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validator instance for the request.
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $instance = parent::getValidatorInstance();

        if (method_exists($this, 'prepareValidator')) {
            $this->prepareValidator($instance);
        }

        return $instance;
    }

    /**
     * Get the validation rules that apply to the current request
     * @return array
     */
    public function rules()
    {
        $action = $this->getCurrentAction();
        $method = camel_case("rules_for_{$action}");

        if (method_exists($this, 'prepare')) {
            $this->prepare();
        }

        if (method_exists($this, $method)) {
            $rules = $this->$method();
        } else {
            $rules = [];
        }

        if ($this->merge && method_exists($this, 'rulesForAll')) {
            $rules = array_merge_recursive($this->rulesForAll(), $rules);
        }

        $rules = $this->replaceRuleVariables($rules, $this->all());

        return $rules;
    }

    /**
     * Construct a unique validation rule.
     * @return string
     */
    protected function unique($column, $table = '<table>', $softDelete = false)
    {
        return "unique:{$table},{$column},<key>" . ($softDelete ? ',id,deleted_at,NULL' : '');
    }

    /**
     * Construct an exists validation rule.
     * @return string
     */
    protected function existsRule($table, $column, $softDelete = false)
    {
        return "exists:{$table},{$column}" . ($softDelete ? ',deleted_at,NULL' : '');
    }

    /**
     * Tell the validator to replace a certain value in the rules. (taken form anlutro/l4-validation)
     * @param  string $key
     * @param  string $value
     * @return void
     */
    public function replaceValue($key, $value)
    {
        if ($value === null) {
            unset($this->replace[$key]);
        } else {
            $this->replace[$key] = $value;
        }
    }

    /**
     * Dynamically replace variables in the rules. (taken form anlutro/l4-validation)
     * @param  array  $rules
     * @param  array  $attributes
     * @return array
     */
    protected function replaceRuleVariables(array $rules, array $attributes)
    {
        array_walk_recursive($rules, function (&$item, $key) use ($attributes) {
            // don't mess with regex rules
            if (substr($item, 0, 6) === 'regex:') {
                return;
            }

            // replace explicit variables
            foreach ($this->replace as $key => $value) {
                if (strpos($item, "<$key>") !== false) {
                    $item = str_replace("<$key>", $value, $item);
                }
            }
            unset($key, $value);

            // replace input variables
            foreach ($attributes as $key => $value) {
                if (strpos($item, "[$key]") !== false) {
                    $item = str_replace("[$key]", $value, $item);
                }
            }
            unset($key, $value);
        });

        return $rules;
    }

    /**
     * Get the current action for the current request.
     * @return string
     */
    protected function getCurrentAction()
    {
        static $action;

        if (!$action) {
            $action = $this->removeVerbs($this->route()->getActionName());
        }

        return $action;
    }

    /**
     * Strip an action of the Http verbs and the controller class name.
     * @param  string $action
     * @return string
     */
    protected function removeVerbs($action)
    {
        $action = substr($action, strpos($action, '@') + 1);
        $verbs = Router::$verbs;

        array_walk($verbs, function (&$verb) {
            $verb = '/^' . strtolower($verb) . '/';
        });

        $action = preg_replace($verbs, '', $action);

        return lcfirst($action);
    }

}
