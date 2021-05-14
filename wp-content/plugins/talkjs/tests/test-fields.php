<?php
/**
 * Class FieldTest
 *
 * @package TalkJS
 */

use TalkJS\Facades\Field;
use TalkJS\Helpers\User;
use TalkJS\Helpers\Settings;

/**
 * Field Tests
 */
class FieldTests extends BaseTest {

	/** Test */
	function test_it_can_generate_a_text_field()
	{
		$this->assertTrue( method_exists( '\TalkJS\Admin\Ui\FieldBuilder', 'text' ) );
		$html = Field::text( 'test' )->render();
		$this->assertContains( '<input type="text"', $html );
	}

	/** Test */
	function test_it_can_generate_a_textarea()
	{
		$this->assertTrue( method_exists( '\TalkJS\Admin\Ui\FieldBuilder', 'textarea' ) );
		$html = Field::textarea( 'test' )->render();
		$this->assertContains( '<textarea', $html );
	}


	/** Test */
	function test_it_can_generate_a_checkbox()
	{
		$this->assertTrue( method_exists( '\TalkJS\Admin\Ui\FieldBuilder', 'checkbox' ) );
		$html = Field::checkbox( 'test' )->render();
		$this->assertContains( '<input type="checkbox"', $html );
	}


	/** Test */
	function test_it_can_generate_a_select()
	{
		$this->assertTrue( method_exists( '\TalkJS\Admin\Ui\FieldBuilder', 'select' ) );
		$html = Field::select( 'test', [ 'choices' => $this->getChoices() ] )->render();
		$this->assertContains( '<select', $html );
	}


	/** Test */
	function test_it_can_take_multiple_select_items()
	{
		$choices = $this->getChoices();
		$values = [ 'two', 'three' ];
		$field = Field::select( 'test', [ 'multiple' => true, 'choices' => $choices, 'value' => $values ]);

		$this->assertTrue( $field->isSelected( 'two' ) );
		$this->assertFalse( $field->isSelected( 'one' ) );

		//it returns an array for the field value:
		$this->assertTrue( is_array( $field->getValue() ) );

		//see if it outputs selected html
		$html = $field->render();
		$this->assertContains( '<option value="two" selected', $html );
	}

	/** Test */
	function test_it_adds_a_hidden_field_to_a_checkbox_to_capture_false_checkboxes()
	{
		$field = Field::checkbox( 'test' );
		$html = $field->render();

		$this->assertContains( '<input type="hidden"', $html );
	}

	/** Test */
	function test_it_generates_a_field_id()
	{
		$field = Field::text( 'test' );
		$this->assertEquals( $field->getFieldId(), 'field-test-text' );

		$html = $field->render();
		$this->assertContains( 'id="field-test-text"', $html );
	}

	/** Test */
	function test_it_renders_a_label_if_set()
	{
		$html = $this->getFieldHtml();
		$this->assertNotContains( '<label', $html );

		$html = $this->getFieldHtml([ 'label' => 'My Label' ]);
		$this->assertContains( 'My Label</label>', $html );
	}


	/** Test */
	function test_it_adds_validation_data()
	{
		$html = $this->getFieldHtml();
		$this->assertNotContains( 'data-validate', $html );

		$html = $this->getFieldHtml([ 'validation' => ['required'] ]);
		$this->assertContains( 'data-validate', $html );
	}


	/** Test */
	function test_it_creates_a_validation_string()
	{
		$field = Field::text( 'test', ['validation' => [ 'required', 'number' ]]);
		$html = $field->render();

		$this->assertEquals( $field->getProp('validation-string'), 'required,number' );
		$this->assertContains( 'data-validate="required,number"', $html );
	}


	/**
	 * Get a field's html
	 *
	 * @param  array  $args
	 *
	 * @return String
	 */
	public function getFieldHtml( $args = [] )
	{
		$field = Field::text( 'test', $args );
		return $field->render();
	}


	/**
	 * Returns test choices for a select box
	 *
	 * @return array
	 */
	public function getChoices()
	{
		return [
			[ 'label' => 'One', 'value' => 'one' ],
			[ 'label' => 'Two', 'value' => 'two' ],
			[ 'label' => 'Three', 'value' => 'three' ]
		];
	}

}
