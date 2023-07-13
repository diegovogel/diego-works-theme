<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="journal-entry__header">
      <h1 class="entry__title"><?php the_title() ?></h1>
    </div>

    <div class="journal-entry__meta">
      <p class="meta__date">
        Posted on <?= get_the_date( 'F j, Y' ) ?>
      </p>
    </div>

    <div class="journal-entry__content">
      <?php the_content() ?>
    </div>

    <div class="journal-entry__author">
      <h2 class="author__heading">Author</h2>

      <div class="author__box">
        <div class="box__header">
          <?php $author_name = get_the_author_meta( 'first_name' ) . ' ' . get_the_author_meta( 'last_name' ) ?>
          <?= get_avatar( $post->post_author, 300, '', 'Profile picture of ' . $author_name ) ?>

          <p class="box__author-name">
            <?= $author_name ?>
          </p>
        </div>

        <div class="box__body">
          <?= get_the_author_meta( 'description' ) ?>

          <a href="/" class="more-link">More about Diego</a>
        </div>
      </div>
    </div>
<?php endwhile;
endif; ?>
<?php get_footer(); ?>