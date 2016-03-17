<?php
  /**
   * Template Name: Frontpage
   */
?>
<?php get_header(); ?>

<div class="hero">
  <div class="hero-content">
    <?php 
      // Check if the title exists
      if( $title = get_theme_mod('hero_title') )
      {
        echo '<h1 class="hero-title">' . $title . '</h1>';
      }
      
      // Check if the subtitle exists
      if( $subtitle = get_theme_mod('hero_subtitle') )
      {
        echo '<p class="hero-description">' . $subtitle . '</p>';
      }
      
      // Check if the page has been selected and it has text
      // a button without link or text isn’t ideal
      if ( $link = get_option('hero_page') && $text = get_theme_mod('hero_button_text') )
      {
        echo '<p><a href="' . get_permalink($link) . '" class="button">' . $text . '</a></p>';
      }
    ?>
  </div>
  <div class="hero-image">
    <?php 
      // Check if the image really exists
      if ( ! empty(hero_image()) && null !== hero_image() )
      {
        echo hero_image();
      }
      // Otherwise start with the default image
      else
      {
        echo '<img src="' . get_template_directory_uri() . '/images/hero-image.svg' . '">';
      }
    ?>
  </div>
</div>

<?php get_footer(); ?>