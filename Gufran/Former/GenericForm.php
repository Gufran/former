<?php namespace Gufran\Former;

use ArrayAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;

abstract class GenericForm extends GenericValidator implements ArrayAccess {

    /**
     * Input class
     * @var Illuminate\Http\Request
     */
    protected $input;

    /**
     * Array of rules
     * @var array
     */
    protected $rules;

    /**
     * Form elements with their values
     * @var array
     */
    protected $elements;

    /**
     * If true then form hydration will be deferred
     * @var boolean
     */
    protected $defer = true;

    /**
     * Whether or not the form has been hydrated
     * @var boolean
     */
    private $hydrated = false;

    /**
     * @param Request   $input     [description]
     * @param Validator $validator [description]
     */
    public function __construct(Request $input, Validator $validator)
    {
        parent::__construct($validator);
        $this->input = $input;

        if(! $this->defer) 
        {
            $this->hydrate();
        }
    }

    /**
     * Hydrate the object with input values and validation rules 
     * @return void
     */
    public function hydrate()
    {
        if($this->hydrated) return;

        $this->rules = $this->getRules();
        $this->elements = $this->input->only(array_keys($this->rules));

        // also fetch any confirmation field
        foreach($this->input->all() as $key => $value) 
        {
            if(mb_substr($key, -12) === 'confirmation')
            {
                $this->elements[$key] = $value;
            }
        }

        $this->hydrated = true;
    }

    /**
     * check whether or not the form is valid
     * @return boolean
     */
    public function validates()
    {
        if( ! $this->hydrated)
        {
            $this->hydrate();
        }

        return $this->runValidation($this->elements, $this->rules);
    }

    /**
     * alias for `validates`, returns true if form is valid
     * @return boolean
     */
    public function isValid()
    {
        return $this->validates();
    }

    /**
     * returns true if the form is not valid
     * @return boolean
     */
    public function isInvalid()
    {
        return ! $this->validates();
    }

    /**
     * get all values from form elements
     * @return array 
     */
    public function getValues()
    {
        $values = array();

        foreach($this->elements as $key => $value)
        {
            if(mb_substr($key, -12) === 'confirmation') continue;

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * get value of a single element from form
     * @param  string $value
     * @return string
     */
    public function get($value)
    {
        return isset($this->elements[$value]) ?: null;
    }

    /**
     * get the validation rules to run against form data
     * @return array
     */
    abstract public function getRules();

    /**
     * check if offset exists on array
     * @param  string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    /**
     * get an element from form elements
     * @param  string $offset
     * @return string
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset];
    }

    /**
     * set value of an element by its offset
     * @param  string $offset
     * @param  string $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $offset;
    }

    /**
     * remove an element from form by its offset
     * @param  string $offset
     * @return void
     */
    public function offsetUnset ($offset)
    {
        unset($this->elements[$offset]);
    }
}