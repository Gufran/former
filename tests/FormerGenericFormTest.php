<?php 

use Mockery as m;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Gufran\Former\GenericForm;

class FormerGenericFormTest extends PHPUnit_Framework_TestCase {
    
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_hydrate_upon_instantiation()
    {
        $request = m::mock('Illuminate\Http\Request');
        $validator = m::mock('Illuminate\Validation\Factory');
        $result = array('name' => 'gufran', 'age' => 20);

        $request->shouldReceive('only')->with(array('name', 'age'))->andReturn($result);
        $request->shouldReceive('all')->andReturn($result);

        $instance = new GenericFormStub($request, $validator);

        $this->assertInstanceOf('Gufran\Former\GenericForm', $instance);
    }

    /**
     * @test
     */
    public function it_behaves_as_an_array()
    {
        $request = m::mock('Illuminate\Http\Request');
        $validator = m::mock('Illuminate\Validation\Factory');
        $result = array('name' => 'gufran', 'age' => 20);

        $request->shouldReceive('only')->with(array('name', 'age'))->andReturn($result);
        $request->shouldReceive('all')->andReturn($result);

        $instance = new GenericFormStub($request, $validator);

        $this->assertInstanceOf('ArrayAccess', $instance);
        $this->assertInstanceOf('IteratorAggregate', $instance);
    }
}

class GenericFormStub extends GenericForm {
    public function getRules() { return array('name' => 'required', 'age' => 'required'); }
}