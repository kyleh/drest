<?php


namespace Drest\Mapping;

/**
 *
 * A class metadata instance that holds all the information for a Drest entity
 * @author Lee
 *
 */
use Drest\DrestException;

class ClassMetaData
{
	const CONTENT_TYPE_SINGLE = 1;
	const CONTENT_TYPE_COLLECTION = 2;

    /**
     * An array of Annotation\Route objects defined on this entity
     * @var array $routes
     */
	protected $routes = array();

	/**
	 *
	 * An array of Drest\Writer\InterfaceWriter object defined on this entity
	 * @var array $writers
	 */
	protected $writers = array();

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $name;

	/**
	 *
	 * Add a route
	 * @param Drest\Mapping\Annotation\Route $route
	 */
	public function addRoute(Annotation\Route $route)
	{
        $this->routes[] = $route;
	}

	/**
	 * Set a writer instance to be used on this resource
	 * @param object|string $writer - can be either an instance of Drest\Writer\InterfaceWriter of a string (shorthand allowed - Json / Xml) referencing the class.
	 */
	public function addWriter($writer)
	{
		if (!is_object($writer) && is_string($writer))
		{
			throw DrestException::writerMustBeObjectOrString();
		}
		if (is_object($writer))
		{
			if (!$writer instanceof \Drest\Writer\InterfaceWriter)
			{
				throw DrestException::unknownWriterClass(get_class($writer));
			}
			$this->writers[get_class($writer)] = $writer;
		} elseif(is_string($writer))
		{
			$classNamespace = 'Drest\\Writer\\';
			if (class_exists($writer, false))
			{
				$this->writers[$writer] = $writer;
			} elseif (class_exists($classNamespace . $writer))
			{
				$this->writers[$classNamespace . $writer] = $classNamespace . $writer;
			} else
			{
				throw DrestException::unknownWriterClass($writer);
			}
		}
	}

	/**
	 * Sets a unique reference name for the resource. If other resources are created with this name an exception is thrown (must be unique)
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

}
