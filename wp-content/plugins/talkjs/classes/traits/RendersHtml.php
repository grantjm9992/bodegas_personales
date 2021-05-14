<?php

	namespace TalkJS\Traits;

	class RendersHtml{

		/**
		 * Generated html
		 * 
		 * @var String
		 */
		protected $html;



		/**
		 * Create the html for the notification badge
		 * 
		 * @return String
		 */
		public function make()
		{
						
			return $this;
		}



		/**
		 * Display the html
		 * 
		 * @return String
		 */
		public function display()
		{
			echo $this->getHtml();
		}

		/**
		 * Return the HTML
		 * 
		 * @return String
		 */
		public function getHtml()
		{
			return $this->html;
		}

	}
