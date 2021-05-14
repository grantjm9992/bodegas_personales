<?php
/**
 * Class WidgetTests
 *
 * @package TalkJS
 */

use TalkJS\Chat\Widget;
use TalkJS\Helpers\Settings;

/**
 * Widget Tests
 */
class WidgetTests extends WP_UnitTestCase {

	/**
	 * A notification gets displayed if there's no api-key setting
	 */
	function test_it_has_fields() {

		$this->assertTrue( method_exists( 'TalkJS\Chat\Widget', 'getFields' ) );
		$widget = $this->getWidget();

		$this->assertNotEmpty( $widget->getFields( $this->getSettings() ) );
	}


	/** Test */
	function test_it_displays_fields()
	{
		$widget = $this->getWidget();
		$fields = array_values( $widget->getFields( $this->getSettings ) );
		$field = $fields[0];

		ob_start();

			$widget->form( $this->getSettings );

		$html = ob_get_clean();

		$this->assertContains( $field->render(), $html );
	}


	/** Test */
	function test_it_can_update_its_title()
	{
		$this->assertSavedAs( 'title', 'My new title' );
	}

	/** Test */
	function test_it_can_update_the_welcome_message()
	{
		$this->assertSavedAs( 'welcome', 'Hi there!' );
	}


	/** Test */
	function test_it_strips_html_from_the_title_field()
	{
		$title = '<h1>My Title</h1>';
		$this->assertSavedAs( 'title', $title, strip_tags( $title ) );
	}


	/**
	 * Check if things are saved the way they ought to be saved
	 *
	 * @return bool
	 */
	public function assertSavedAs( $field, $new, $expected = null )
	{
		if( is_null( $expected ) )
			$expected = $new;

		$widget = $this->getWidget();
		$settings = $widget->update([ $field => $new ], []);

		$this->assertSame( $settings[ $field ], $expected );
	}



	/**
	 * Returns a functioning widget
	 *
	 * @return WP_Widget instance
	 */
	public function getWidget()
	{
		$widget = new Widget();
		$widget->update( $this->getSettings(), [] );
		return $widget;
	}

	/**
	 * Returns the settings as an array
	 *
	 * @return Array
	 */
	public function getSettings()
	{
		$settings = [
			'title' => 'Chat',
			'welcome' => 'hi there',
			'chatWith' => 'author'
		];

		return $settings;
	}

}
