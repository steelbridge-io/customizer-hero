<?php
/**
 * Contains methods for customizing the theme customization screen.
 * Using the example class from with modifications
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */
class Hero_Customize 
{ 
  /**
   * Register all fields necessary for editing the hero area
   */
  public static function register($wp_customize)
  { 
    $transport = ( $wp_customize->selective_refresh ? 'postMessage' : 'refresh' );
    
    // Add hero section
    $wp_customize->add_section( 'hero', array(
      'title' => 'Hero',
      'priority' => 0
    ));
    
    // Add setting & control for hero title
    $wp_customize->add_setting( 'hero_title', array(
      'default' => 'Edit live with the Wordpress Customizer',
      'transport' => $transport
    ));
    
    $wp_customize->add_control( 'hero_title', array(
      'label' => 'Title',
      'section' => 'hero',
      'settings' => 'hero_title',
      'type' => 'text'
    ));
    
    // Add setting & control for hero subtitle
    $wp_customize->add_setting( 'hero_description', array(
      'default' => 'Using the Wordpress Customizer you can make updates to parts of your website with a live preview. This makes it easy to iterate on changes.',
      'transport' => $transport
    ));
    
    $wp_customize->add_control( 'hero_description', array(
      'label' => 'Description',
      'section' => 'hero',
      'settings' => 'hero_description',
      'type' => 'textarea'
    ));
    
    // Add setting & control for hero image
    $wp_customize->add_setting( 'hero_image', array(
      'default' => get_template_directory_uri() . '/images/hero-image.svg',
      'transport' => $transport
    ));
    
    $wp_customize->add_control(
      new WP_Customize_Cropped_Image_Control( $wp_customize, 'hero_image', array(
        'label' => 'Image',
        'section' => 'hero',
        'context' => 'hero-image',
        'flex_width' => false,
        'flex_height' => true,
        'width' => 1080,
        'height' => 1080
      ) )
    );
    
    // Add setting & control for hero background color
    $wp_customize->add_setting( 'hero_background_color', array(
      'default' => '#c3f2f5',
      'transport' => $transport
    ));

    $wp_customize->add_control( 
      new WP_Customize_Color_Control( $wp_customize, 'hero_background_color', array(
        'label' => 'Background color',
        'section' => 'hero',
        'settings' => 'hero_background_color'
      ) ) 
    );
    
    // Select a page
    $wp_customize->add_setting( 'hero_page', array(
      'type' => 'option',
      'transport' => 'none'
    ));

    $wp_customize->add_control( 'hero_page', array(
      'label' => 'Link to page',
      'section' => 'hero',
      'type' => 'dropdown-pages',
      'settings' => 'hero_page'
    ));
    
    // Add setting & control for button text
    $wp_customize->add_setting( 'hero_button_text', array(
      'default' => 'Find out how',
      'transport' => $transport
    ));
    
    $wp_customize->add_control( 'hero_button_text', array(
      'label' => 'Button text',
      'section' => 'hero',
      'settings' => 'hero_button_text',
      'type' => 'text'
    ));
  }
  
  public function refresh( WP_Customize_Manager $wp_customize )
  { 
    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) return;
    
    // Title
    $wp_customize->selective_refresh->add_partial('hero_title', array(
      'selector' => '.hero-title',
      'settings' => 'hero_title',
      'render_callback' => function() {
        return get_theme_mod('hero_title');
      }
    ) );
    
    // Description
    $wp_customize->selective_refresh->add_partial('hero_description', array(
      'selector' => '.hero-description',
      'settings' => 'hero_description',
      'render_callback' => function() {
        return get_theme_mod('hero_description');
      }
    ) );
    
    // Image
    $wp_customize->selective_refresh->add_partial('hero_image', array(
      'selector' => '.hero-image img',
      'settings' => 'hero_image',
      'render_callback' => self::hero_image_partial()
    ) );
    
    // Background colour
    $wp_customize->selective_refresh->add_partial('hero_background_color', array(
      'selector' => '#hero-css',
      'settings' => 'hero_background_color',
      'render_callback' => function() {
        self::css('.hero', 'background-color', 'hero_background_color');
      }
    ) );
    
    // Button text
    $wp_customize->selective_refresh->add_partial('hero_button_text', array(
      'selector' => '.hero .button',
      'settings' => 'hero_button_text',
      'render_callback' => function() {
        return get_theme_mod('hero_button_text');
      }
    ) );
  }
  
  /**
   * For hooking into `wp_head` mostly to output CSS
   */
  public function output()
  {
    echo '<style id="hero-css">';
    echo self::css('.hero', 'background-color', 'hero_background_color');
    echo '</style>';
  }

  /**
   * This will generate a line of CSS for use in header output. If the setting
   * ($mod_name) has no defined value, the CSS will not be output.
   * 
   * @uses get_theme_mod()
   * @param string $selector CSS selector
   * @param string $property The name of the CSS *property* to modify
   * @param string $mod_name The name of the 'theme_mod' option to fetch
   * @param bool $echo Optional. Whether to print directly to the page (default: true).
   * @return string Returns a single line of CSS with selectors and a property.
   */
  public static function css( $selector, $property, $theme_mod )
  {
    $return = '';
    $theme_mod = get_theme_mod($theme_mod);

    if ( ! empty( $theme_mod ) )
    {
      $return = sprintf('%s { %s:%s; }',
        $selector,
        $property,
        $theme_mod
      );

      return $return;
    }
  }
  
  /**
   * Reusable partials
   */
  public static function hero_image_partial()
  {
    return wp_get_attachment_image(get_theme_mod('hero_image'), 'hero-image');
  }
}

// Setup the Theme Customizer settings and controls
add_action( 'customize_register', array('Hero_Customize', 'register') );

// Setup the selective refresh functionality
add_action( 'customize_register', array('Hero_Customize', 'refresh') );

// Output custom CSS to live site
add_action( 'wp_head', array('Hero_Customize' , 'output') );

/**
 * Friendlier access for template files
 */
function hero_image()
{
  return Hero_Customize::hero_image_partial();
}