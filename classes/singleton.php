<?php

namespace BEAPI\Maintenance_Mode;

trait Singleton {

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @return static
	 */
	final public static function get_instance() {
		/**
		 * @psalm-suppress UnsafeInstantiation
		 */
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Constructor protected from the outside
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Add init function by default
	 * Implement this method in your child class
	 * If you want to have actions send at construct
	 *
	 * @return void
	 */
	protected function init() {
	}

	/**
	 * prevent the instance from being cloned
	 *
	 * @throws \LogicException
	 */
	final public function __clone() {
		throw new \LogicException( 'A singleton must not be cloned!' );
	}

	/**
	 * prevent from being serialized
	 *
	 * @throws \LogicException
	 */
	final public function __sleep() {
		throw new \LogicException( 'A singleton must not be serialized!' );
	}

	/**
	 * prevent from being unserialized
	 *
	 * @throws \LogicException
	 */
	final public function __wakeup() {
		throw new \LogicException( 'A singleton must not be unserialized!' );
	}

	/**
	 * Destruct your instance
	 *
	 * @return void
	 */
	final public static function destroy() {
		static::$instance = null;
	}
}
