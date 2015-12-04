<?php

use OhMyPhp\ValueObjectsInPhpStuckBalls;
use OhMyPhp\ValueObjectTrait;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueObjectsInPhpStuckBallsTest extends \PHPUnit_Framework_TestCase {

	public function testWhenAllFieldsAreSet_thingsAreFluffy() {
		$vo = new SuckyPhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';
		$vo->baz = 'all the things';

		$this->assertInstanceOf( SuckyPhpValueObject::class, $vo->safelyGet() );

		$this->assertSame( 'cats', $vo->safelyGet()->foo );
		$this->assertSame( 'kittens', $vo->safelyGet()->bar );
		$this->assertSame( 'all the things', $vo->safelyGet()->baz );

		$this->assertTrue( $vo->safelyGet()->isComplete() );
		$vo->validate();
	}

	public function testWhenOneFieldIsNotSet_theTraitGetsVeryAngry() {
		$vo = new SuckyPhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';

		$this->assertFalse( $vo->isComplete() );

		$this->setExpectedException( RuntimeException::class );
		$vo->validate();
	}

	public function testWhenOneFieldIsNotSet_theTraitIsStillVeryAngry() {
		$vo = new SuckyPhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';

		$this->setExpectedException( RuntimeException::class );
		$vo->safelyGet()->bar;
	}

	public function testTraitAliasWorks() {
		$vo = new PhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';
		$vo->baz = 'all the things';

		$this->assertSame( 'kittens', $vo->safelyGet()->bar );
	}

	public function testNullPrivatePropertiesAreInfuriating() {
		$vo = new ExtraSuckyPhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';
		$vo->baz = 'all the things';

		$this->assertFalse( $vo->isComplete() );

		$this->setExpectedException( RuntimeException::class );
		$vo->validate();
	}

	public function testNotNullPrivatePropertiesAreCool() {
		$vo = new ExtraSuckyPhpValueObject();
		$vo->foo = 'cats';
		$vo->bar = 'kittens';
		$vo->baz = 'all the things';
		$vo->makeBlahNotBeNull();

		$this->assertTrue( $vo->isComplete() );
		$vo->validate();
	}

}

class SuckyPhpValueObject {
	use ValueObjectsInPhpStuckBalls;

	public $foo;
	public $bar;
	public $baz;

}

class PhpValueObject {
	use ValueObjectTrait;

	public $foo;
	public $bar;
	public $baz;

}

class ExtraSuckyPhpValueObject{
	use ValueObjectsInPhpStuckBalls;

	public $foo;
	public $bar;
	public $baz;

	private $blah;

	public function makeBlahNotBeNull() {
		$this->blah = (bool)'Value objects in PHP suck balls';
	}

}