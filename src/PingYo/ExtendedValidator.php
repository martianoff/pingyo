<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 15.09.14
 * Time: 19:57
 */

namespace PingYo;

class ExtendedValidator extends \Valitron\Validator {
    protected function validateRequired_with($field, $value, array $params)
    {
        $field2 = $params[0];

        if(isset($this->_fields[$field2])){
            if((!is_null($this->_fields[$field2]))&&(!empty($this->_fields[$field2])))
                return $this->validateRequired($field, $value);
            else
                return true;
        }
        else
            return true;
    }

    protected function validateRequired_without($field, $value, array $params)
    {
        $field2 = $params[0];

        if(isset($this->_fields[$field2])){
            if((!is_null($this->_fields[$field2]))&&(!empty($this->_fields[$field2])))
                return true;
            else
                return $this->validateRequired($field, $value);
        }
        else
            return $this->validateRequired($field, $value);
    }

    protected function validateRequired_if($field, $value, array $params)
    {
        $field2 = $params[0][0];
        $field2_values = $params[0][1];

        if(isset($this->_fields[$field2])){
            if((!is_null($this->_fields[$field2]))&&(!empty($this->_fields[$field2])))
            {
                if(in_array($this->_fields[$field2], $field2_values))
                    return $this->validateRequired($field, $value);
                else
                    return true;
            }
            else
                return true;
        }
        else
            return true;
    }

    protected function validatePhone($field, $value, array $params)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $field2 = $params[0];
            $phone_country = $this->_fields[$field2];
            $swissNumberProto = $phoneUtil->parse($value, $phone_country);
            if($phoneUtil->isValidNumber($swissNumberProto))
                return true;
            else
                return false;
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    function __construct($input){
        if(is_callable('parent::__construct')) {
            parent::__construct($input);
        }
    }

    public function validate()
    {
        foreach ($this->_validations as $v) {
            foreach ($v['fields'] as $field) {
                list($values, $multiple) = $this->getPart($this->_fields, explode('.', $field));

                // Don't validate if the field is not required and the value is empty
                if ($v['rule'] !== 'required' && $v['rule'] !== 'required_without' && $v['rule'] !== 'required_with' && $v['rule'] !== 'required' && !$this->hasRule('required_if', $field) && (! isset($values) || $values === '' || ($multiple && count($values) == 0))) {
                    continue;
                }

                // Callback is user-specified or assumed method on class
                if (isset(static::$_rules[$v['rule']])) {
                    $callback = static::$_rules[$v['rule']];
                } else {
                    $callback = array($this, 'validate' . ucfirst($v['rule']));
                }

                if (!$multiple) {
                    $values = array($values);
                }

                $result = true;
                foreach ($values as $value) {
                    $result = $result && call_user_func($callback, $field, $value, $v['params']);
                }

                if (!$result) {
                    if(is_array($v['params'])) {
                        foreach ($v['params'] as $pname=>$param) {
                            if (is_array($param)) {
                                $better_param = array();
                                foreach ($param as $subparam) {
                                    if (is_array($subparam)) {
                                        $better_param[] = "['" . implode("', '", $subparam) . "']";
                                    } else {
                                        $better_param[] = $subparam;
                                    }
                                }
                                $v['params'][$pname] = $better_param;
                            }
                        }
                    }
                    $this->error($field, $v['message'], $v['params']);
                }
            }
        }

        return count($this->errors()) === 0;
    }
} 