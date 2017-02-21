<?php /*
Template Name: Work
*/ ?>

<?php get_header(); ?>

<section id="content" class="work">

	

	<div id="work" class="interior">
		

		<?php if(have_posts()): while(have_posts()): the_post(); ?>

			<?php 
				$project = new Work_Module(get_the_id());
			?>
			<?php // pre_print_r($project->get_modules()); ?>
			<?php $project->render_project(); ?>

		<?php endwhile; endif; ?>

	</div>



</section>
<?php //end content ?>


<script type="text/javascript">

	jQuery(document).ready(function($){});
</script>

<?php get_footer(); ?>