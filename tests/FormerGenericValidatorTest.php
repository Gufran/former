<?php 

use Mockery as m;
use Gufran\Former\GenericValidator;

class FormerGenericValidatorTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_initialize_with_illuminate_validator()
    {
        $validator = m::mock('Illuminate\Validation\Factory');
        $instance = new GenericValidator($validator);
        $this->assertInstanceOf('Gufran\Former\GenericValidator', $instance, 'Initialization failed with Validator');
    }

    /**
     * @test
     */
    public function it_validates_data_against_rules_and_return_boolean()
    {
        $data = array('hello', 'world');
        $rules = array('bye', 'world');

        $validator = m::mock('Illuminate\Validation\Factory');
        $validation = m::mock(array('messages' => array('first' => 'hello'), 'passes' => true));
        $instance = new GenericValidator($validator);

        $validator->shouldReceive('make')->with($data, $rules)->andReturn($validation);

        $this->assertTrue($instance->runValidation($data, $rules));
    }

    /**
     * @test
     */
    public function it_returns_validation_error_messages_as_array()
    {
        $data = array('hello', 'world');
        $rules = array('bye', 'world');

        $validator = m::mock('Illuminate\Validation\Factory');
        $validation = m::mock(array('messages' => array('first' => 'hello'), 'passes' => true));
        $instance = new GenericValidator($validator);

        $validator->shouldReceive('make')->with($data, $rules)->andReturn($validation);

        $instance = new GenericValidator($validator);
        $instance->runValidation($data, $rules);

        $this->assertInternalType('array', $instance->messages(), 'Does not return validation messages as array');
    }
}