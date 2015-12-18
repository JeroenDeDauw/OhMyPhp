<?php

namespace OhMyPhp;

/**
 * Dear PHP, please just let me define a set of named values that then form an immutable object
 * in such a way type safety is not lost and tools still understand what is going on.
 *
 * This trait helps with the named constructor arguments problem:
 *
 * $yourThing = YourThing::newInstance()
 * 					->withFoo( 'some foo' ),
 * 					->withBar( 'some bar' ),
 * 					->withBaz( 1337 );
 *
 * If you use the above pattern you introduce the problem that builders of the value might
 * not set all requires fields, leading to errors later on. That is where this trait comes
 * in. It won't help if you have required fields that can be null and it won't help static
 * code analysis tools to find places where you missed setting a required value.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
trait ValueObjectsInPhpSuckBalls {

	public static function newInstance() {
		return new self();
	}

	/**
	 * Throws an exception if any of the fields have null as value.
	 *
	 * @throws \RuntimeException
	 */
	public function validate() {
		foreach ( get_object_vars( $this ) as $fieldName => $fieldValue ) {
			if ( $fieldValue === null ) {
				throw new \RuntimeException( "Field '$fieldName' cannot be null" );
			}
		}
	}

	/**
	 * Returns if all fields have a non-null value.
	 */
	public function isComplete() {
		return $this->getFirstMissingFieldName() === null;
	}

	/**
	 * @return string|null
	 */
	private function getFirstMissingFieldName() {
		foreach ( get_object_vars( $this ) as $fieldName => $fieldValue ) {
			if ( $fieldValue === null ) {
				return $fieldName;
			}
		}

		return null;
	}

	/**
	 * Gets the field value if it was set to non-null value or throws an exception if it was not.
	 *
	 * @return $this
	 * @throws \RuntimeException
	 */
	public function safelyGet() {
		$this->validate();
		return $this;
	}

}
