<?php get_header(); ?>

<section class="home-hero">
  <div class="home-hero__image">
    <?php
    $image = get_field('hero_image');
    if (!empty($image)) : ?>
      <div class="image-wrapper">
        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
      </div>
    <?php endif; ?>
  </div>

  <div class="home-hero__text">
    <h2 class="home-hero__heading">
      <?php the_field('hero_heading') ?>
    </h2>

    <?php the_field('hero_body') ?>
  </div>
</section>

<section class="home-skills page-section">
  <?php get_template_part( 'partials/fancy-heading', null, ['heading' => 'Skills', 'subheading' => get_field('experience_subheading')] ) ?>

  <div class="home-skills__skills">
    <?php the_field('skills') ?>
  </div>
</section>

<section class="home-work page-section">
  <?php get_template_part( 'partials/fancy-heading', null, ['heading' => 'Work', 'subheading' => get_field('work_subheading')] ) ?>

  <div class="home-work__projects">
    <?php
    global $post;
    $work_posts = get_posts([
      'post_type' => 'project',
      'numberposts' => 4,
      'orderby' => 'menu_order',
    ]);
    foreach ($work_posts as $post) {
      setup_postdata($post);
      ?>
      
      <div class="project-card">
        <?php if ( get_field('main_image') ) : $image = get_field('main_image'); ?>
          <img class="card__image" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"/>
        <?php endif; ?>
        
        <div class="card__text">
          <h4 class="card__heading"><?php the_title() ?></h4>

          <div class="card__tags">
            <?= strip_tags(get_the_term_list( get_the_ID(  ), 'skill', 'Tech: ', ', ' )) ?>
          </div>

          <div class="card__project-description">
            <?php the_field('project_description') ?>
          </div>

          <div class="card__my-role">
            <h5 class="my-role__heading">My role:</h5>

            <?php the_field('my_role') ?>
          </div>

          <?php
          if (get_field('live_link')) {
            ?>
            <a href="<?php the_field('live_link') ?>" class="card__button button button--outline button--small" target="_blank">View Live</a>
            <?php
          }
          ?>
        </div>
      </div>

      <?php
    }
    wp_reset_postdata();
    ?>
  </div>
</section>

<section class="home-journal page-section">
  <?php get_template_part( 'partials/fancy-heading', null, ['heading' => 'Journal', 'subheading' => get_field('journal_subheading')] ) ?>

  <div class="home-journal__entries">
    <?php
      global $post;
      $journal_posts = get_posts([
        'post_type' => 'post',
      ]);
      foreach ($journal_posts as $post) {
        setup_postdata($post);
        ?>
    
        <div class="journal-entry">
          <h4 class="entry__title"><?php the_title() ?></h4>
          
          <div class="entry__excerpt">
            <?php the_excerpt() ?>
          </div>

          <div class="entry__tags">
            <?= strip_tags(get_the_tag_list( 'Tags: ', ', ', '', get_the_ID() )) ?>
          </div>
        </div>
        <?php
      }
      wp_reset_postdata();
      ?>
  </div>
</section>

<?php get_footer(); ?>