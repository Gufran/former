<?php namespace Gufran\Former;

use Illuminate\Validation\Factory as Validator;

class GenericValidator {

    /**
     * Validator class
     * @var Illuminate\Validation\Factory
     */
    private $validator;

    /**
     * Errors occurred in validation
     * @var Illuminate\Support\MessageBag
     */
    private $errorBag;

    /**
     * @param Illuminate\Validation\Factory $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Run validation on data against rules
     * @param  array  $data 
     * @param  array  $rules
     * @return boolean
     */
    public function runValidation(array $data, array $rules)
    {
        $validation = $this->validator->make($data, $rules);
        $this->errorBag = $validation->messages();
        return $validation->passes();
    }

    /**
     * get the message bag
     * @return Illuminate\Support\MessageBag
     */
    public function messages()
    {
        return $this->errorBag;
    }
}