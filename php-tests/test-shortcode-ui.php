<?php

class Test_Shortcode_UI extends WP_UnitTestCase {

	public function test_plugin_loaded() {
		$this->assertTrue( function_exists( 'shortcode_ui_register_for_shortcode' ) );
	}

	public function test_expected_fields() {
		$fields = Shortcode_UI_Fields::get_instance()->get_fields();
		$this->assertArrayHasKey( 'attachment', $fields );
		$this->assertArrayHasKey( 'color', $fields );
		$this->assertArrayHasKey( 'post_select', $fields );
	}

	public function test_register_shortcode_malicious_html() {
		Shortcode_UI::get_instance()->register_shortcode_ui( 'foo', array(
			'inner_content'       => array(
				'label'           => '<script>gotcha()</script>',
				'description'     => '<iframe src="baddomain.com"></iframe>',
				),
			'attrs'               => array(
				array(
					'attr'        => 'bar',
					'label'       => '<strong>gotcha()</strong>',
					'description' => '<script>banana()</script>',
					),
				),
			) );
		$shortcodes = Shortcode_UI::get_instance()->get_shortcodes();
		$this->assertEquals( 'gotcha()', $shortcodes['foo']['inner_content']['label'] );
		$this->assertEmpty( $shortcodes['foo']['inner_content']['description'] );
		$this->assertEquals( '<strong>gotcha()</strong>', $shortcodes['foo']['attrs'][0]['label'] );
		$this->assertEquals( 'banana()', $shortcodes['foo']['attrs'][0]['description'] );
	}

}
