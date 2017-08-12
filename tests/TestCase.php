<?php
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
    /**
     * Set protected/private attributes of a class.
     * @param object &$object         Instantiated object attribute of wich will be set.
     * @param string $attributeName Name of the attribute to set.
     * @param mixed $value         Value that will be assigned to the attribute.
     */
    public function setAttribute(&$object, $attributeName, $value)
    {
        $reflection = new \ReflectionProperty(get_class($object), $attributeName);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }
    /**
     * Get protected/private attributes of a class.
     * @param object &$object         Instantiated object attribute of wich will be set.
     * @param string $attributeName Name of the attribute to set.
     *
     * @return mixed Value of a property.
     */
    public function getAttribute(&$object, $attributeName)
    {
        $reflection = new \ReflectionProperty(get_class($object), $attributeName);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }
}
