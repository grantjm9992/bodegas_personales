<?php

	namespace TalkJS\Traits;

	class HasProperties{

		/**
		 * Attributes
		 *
		 * @var Array
		 */
		protected $properties;

		/**
		 * Get a property
		 *
		 * @param String $name
		 * @param Mixed $default
		 *
		 * @return Mixed
		 */
		public function getProp( $name, $default = null )
		{
			$lower_name = strtolower($name);

			if( isset( $this->properties[ $lower_name ] ) )
				return $this->properties[ $lower_name ];

			if( isset( $this->properties[ $name ] ) )
				return $this->properties[ $name ];

			return $default;
		}


		/**
		 * Set a property
		 *
		 * @param String $name
		 * @param Mixed $value
		 *
		 * @return HasProperties
		 */
		public function setProp( $name, $value )
		{
			$name = strtolower($name);

			$this->properties[ $name ] = $value;
			return $this;
		}


		/**
		 * Returns all properties
		 *
		 * @return Array
		 */
		public function allProps()
		{
			return $this->properties;
		}


		/**
		 * Returns all properties as json
		 *
		 * @return String (json)
		 */
		public function allPropsAsJson()
		{
			return json_encode( $this->allProps() );
		}


		/**
		 * Attribute sanitizer
		 *
		 * @return Array
		 */
		public function sanitizeProps( $props )
		{
			return $props;
		}

	}
