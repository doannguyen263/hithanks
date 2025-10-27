<?php $get_sub = get_field('sub');?>
<header class="entry-header page__headerv2 wow fadeInLeft pt-3">
  <div class="container">
    <?php the_title( '<h1 class="page-titlev2 mb-0 text-md-end">', '</h1>' ); ?>
    <hr>
  </div>
</header><!-- .entry-header -->


<div class="container">

	<div class="about-heading">
	  <h2 class="about-heading__title">
	  	<?php if( $get_sub ):
	  			echo $get_sub;
	  		else:
	  			the_title();
	  	endif; ?>
	  </h2>
	</div>
	<hr>

	<article class="page__content">
		<div class="entry-content">
		    <?php the_content(); ?>
		</div>
	</article>
</div>
