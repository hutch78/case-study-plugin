<?php
/*
 * @Author Jeremy HUTCH Hutchcraft - jhutchcraft.com
 * @Version 1.3
 */
class Work_Module{

    public $post_id;
    public $modules;
    public $post_title;

    function __construct($post_id) {
        $this->post_id = $post_id;
        $this->post_title = get_the_title($post_id);
    }

    /**
    * if $post_id property is defined, return it, else throw error
    * @return array
    */
    public function get_post_id(){
        if(isset($this->post_id)){
            return $this->post_id;
        } else {
            return 'WTF MATE';
        }
    }

    /**
    * Get Module (custom fields attached to post_id)
    * @return array
    */
    public function get_modules(){

        $this->modules = get_field('modules', $this->post_id);

        return $this->modules;

    }


    /**
    * Render global css data containers
    * @return array
    */
    public function render_css_data($pid = null){

        $pid = $pid ?: $this->post_id;

        $css_data = array(
            'hex_logo' => get_field('hex_logo', $pid),
            'hex_socials' => get_field('hex_socials', $pid),
            'hex_nav_items' => get_field('hex_nav_items', $pid),
            'hex_nav_items_hover' => get_field('hex_nav_items_hover', $pid),
        );
        // pre_print_r($css_data);

        ?>

            <div class="css-data-container">
                <?php if(isset($css_data['hex_logo']) && $css_data['hex_logo'] != ''){ ?><i class="hex-box" id="logo-color" data-hex="<?php echo $css_data['hex_logo']; ?>"></i><?php } ?>
                <?php if(isset($css_data['hex_socials']) && $css_data['hex_socials'] != ''){ ?><i class="hex-box" id="socials-color" data-hex="<?php echo $css_data['hex_socials']; ?>"></i><?php } ?>
                <?php if(isset($css_data['hex_nav_items']) && $css_data['hex_nav_items'] != ''){ ?><i class="hex-box" id="nav-items-color" data-hex="<?php echo $css_data['hex_nav_items']; ?>"></i><?php } ?>
                <?php if(isset($css_data['hex_nav_items_hover']) && $css_data['hex_nav_items_hover'] != ''){ ?><i class="hex-box" id="nav-items-hover-color" data-hex="<?php echo $css_data['hex_nav_items_hover']; ?>"></i><?php } ?>
            </div>

        <?php

    }



    /**
    * Render home tiles - print global css data containers, project logos, and solid background tiles
    * @return array
    */
    public function render_home_tiles(){

        $args = array(
            'post_type' => 'otml_work',
            'posts_per_page' => -1
        );
        $projects = get_posts($args);
        foreach($projects as $key => $post){ setup_postdata($post);

            $pid = $post->ID;

            $pid = $pid ?: $this->post_id;

            $logo_color = get_field('hex_logo', $pid);

            /* Obtain Thumbnail Information */
            $thumb_id = get_post_thumbnail_id($pid);
            if($thumb_id){
                $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'work-logo', true);
                $thumb_url = $thumb_url_array[0];

                $pattern = '/default.png/';
                $is_default_img = preg_match($pattern, $thumb_url);
            }

            ?>

            <a href="<?php echo get_permalink($pid); ?>" class="a-home-tile dtc" data-bgc="<?php echo $this->get_hex_bgc($pid); ?>">

                <?php if (isset($thumb_url) && !$is_default_img): ?>
                    <div class="outer">
                        <div class="inner">
                            <img class="work-logo" src="<?php echo $thumb_url ?>" alt="Logo for <?php echo $this->post_title; ?> - a project by John Oates">
                        </div>
                    </div>
                <?php endif; ?>

                <span class="view-work" data-hex="<?php echo $logo_color; ?>">View Work</span>

            </a>


        <?php }

    }

    /**
    * Render project - print project header, global css data containers, project modules
    * @return array
    */
    public function render_project(){

        $this->render_css_data();
        $this->render_project_header();
        $this->render_modules();
        $this->render_footer();

    }

    /**
    * Render home tiles - print global css data containers, project logos, and solid background tiles
    * @return array
    */
    public function render_project_header(){

        ?>

            <div class="work-header dtc" data-bgc=<?php echo $this->get_hex_bgc(); ?>>

                <div class="outer">
                    <div class="inner">
                        <div class="header-content">
                            
                            <img src="<?php echo $this->get_featured_img(); ?>" alt="alt="Logo for <?php $this->post_title; ?> - a project by John Oates"" class="work-logo">

                            <div class="the-content">
                                <?php the_content(); ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


        <?php
    }

    /**
    * Get Module (custom fields attached to post_id)
    * @return array
    */
    public function render_modules(){

        $mods = $this->get_modules();

        if ($mods) {
            foreach($mods as $key => $module){

                switch($module['module_type']):
                    case 'two_col':
                        $this->module_two_col($module);
                        break;
                    case 'small_image':
                        $this->module_small_image($module);
                        break;
                    case 'image':
                        $this->module_full_w_image($module, null, 'img');
                        break;
                    case 'background_image':
                        $this->module_full_w_image($module, null, 'bg-img');
                        break;
                endswitch;

            }
        } else {
            // echo "<h2>We're Sorry, there has been no content added to this project yet. Please check back soon!</h2>";
        }

    }

    /**
    * Print Project footer
    * @return array
    */
    public function render_footer(){

        $next_post = get_next_post();
        $prev_post = get_previous_post();
        // $debugger = array(print_r($prev_post, 1), print_r($next_post, 1));
        // pre_print_r($debugger);
        $work_footer_content = get_field('work_footer_content', $this->post_id);

        if (is_a($next_post, 'WP_Post')) {
            $next_post_bg = get_field('next_btn_hover_bg', $next_post->ID);
        }
        if (is_a($prev_post, 'WP_Post')) {
            $prev_post_bg = get_field('next_btn_hover_bg', $prev_post->ID);
        }

        $has_both = (is_a($prev_post, 'WP_Post') && is_a($next_post, 'WP_Post'));

        ?>

            <div class="work-footer dtc" data-bgc="<?php echo $this->get_hex_bgc(); ?>">

                <?php if(isset($prev_post_bg) && $prev_post_bg != ''){ ?><div class="hover-wallpaper next" style="background-image: url('<?php echo $prev_post_bg['url']; ?>');"></div><?php } ?>
                <?php if(isset($next_post_bg) && $next_post_bg != ''){ ?><div class="hover-wallpaper prev" style="background-image: url('<?php echo $next_post_bg['url']; ?>');"></div><?php } ?>
                
                <div class="center">
                    
                    <div class="outer">
                        <div class="inner">

                            <div class="work-footer-content">
                                <h3 class="work-footer-heading"><?php echo $this->post_title; ?></h3>
                                <?php if (isset($work_footer_content) && $work_footer_content != ''): ?>
                                    <?php echo $work_footer_content; ?>
                                <?php endif ?>
                            </div>
                            <div class="work-footer-nav<?php if($has_both){ echo ' has-both-links'; } ?>">
                                
                                <?php if (is_a($next_post, 'WP_Post')): ?>
                                    <a href="<?php echo get_permalink($next_post->ID) ?>" class="nav-btn prev" data-dir="prev">PREVIOUS</a>
                                <?php else: ?>
                                    <?php // <span href="#" class="nav-btn prev disabled" data-dir="prev">PREVIOUS</span> ?>
                                <?php endif ?>
                                <?php if (is_a($prev_post, 'WP_Post')): ?>
                                    <a href="<?php echo get_permalink($prev_post->ID) ?>" class="nav-btn next" data-dir="next">NEXT</a>
                                <?php else: ?>
                                    <?php // <span href="#" class="nav-btn next disabled" data-dir="next">NEXT</span> ?>
                                <?php endif ?>

                            </div>

                        </div>
                    </div>

                </div>

            </div>


        <?php


    }

    /**
    * MODULE - Two Column
    * @return array
    */
    public function module_two_col($module, $debug = false){

        if($debug){
            pre_print_r($module);
        }
        $cols = $module['columns'][0];

        $image = $cols['image']['url'];
        $content = $cols['content'];
        $hex_text_col_bgc = $cols['text_col_bgc'];
        if(isset($cols['images']) && count($cols['images']) == 2){
            $image1 = $cols['images'][0]['url'];
            $image2 = $cols['images'][1]['url'];
        }

        ?> <div class="a-module two-col <?php if(isset($module['cols_layout']) && $module['cols_layout'] != ''){ echo $module['cols_layout']; } ?>"> <?php

            switch($module['cols_layout']):
                case 'img_left': ?>
                    
                    <div class="left-col img-col" style="background-image: url('<?php if(isset($image) && $image != ''){ echo $image; } ?>');"></div>
                    <div class="right-col txt-col dtc" data-bgc="<?php if(isset($hex_text_col_bgc) && $hex_text_col_bgc != ''){ echo $hex_text_col_bgc; } ?>">
                        <div class="outer">
                            <div class="inner">
                                <div class="the-content">
                                    <?php if(isset($content) && $content != ''){ echo $content; } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php break;
                case 'img_right': ?>
                    
                    <div class="left-col txt-col dtc" data-bgc="<?php echo $hex_text_col_bgc; ?>">
                        <div class="outer">
                            <div class="inner">
                                <div class="the-content">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-col img-col dtc" style="background-image: url('<?php if(isset($image) && $image != ''){ echo $image; } ?>');"></div>
        
                   <?php break;
                case 'double_img': ?>
                    <div class="left-col img-col" style="background-image: url('<?php if(isset($image2) && $image2 != ''){ echo $image2; } ?>');"></div>
                    <div class="right-col img-col" style="background-image: url('<?php if(isset($image1) && $image1 != ''){ echo $image1; } ?>');"></div>
                    <?php break;
            endswitch;

        ?> <div class="clear"></div>
        </div> <?php

    }

    /**
    * MODULE - Small, Centered, Image with Background Image
    * @return array
    */
    public function module_small_image($module, $debug = false){

        if($debug){
            pre_print_r($module);
        }

        $bg_type = $module['background_type'];
        switch($bg_type):
            case 'solid':
                $hex_bgc = $module['hex_small_img_bgc'];
                break;
            case 'bg_image':
                $bg_img = $module['small_img_bg']['url'];
                break;
        endswitch;
        $image = $module['image']['url'];

        ?>

            <div class="a-module small-img" data-bgc="<?php if(isset($hex_bgc) && $hex_bgc != ''){ echo $hex_bgc; } ?>">
                
                <div class="inner-wrap">
                    <img class="the-img" src="<?php echo $image; ?>" alt="Case study image for <?php echo $this->post_title; ?>, a project by John Oates">
                </div>

            </div>

        <?php

    }

    /**
    * MODULE - Full Width Image
    * @return array
    */
    public function module_full_w_image($module, $debug = false, $style = 'img'){

        if($debug){
            pre_print_r($module);
        }

        $image = $module['image']['url'];
        $height = $module['height'];

        switch($style):
            case 'img': ?>

                    <div class="a-module full-w-image img">
                        
                        <img class="work-img" src="<?php echo $image; ?>" alt="Case study image for <?php echo $this->post_title; ?>, a project by John Oates">

                    </div>

                <?php break;
            case 'bg-img': ?>

                    <div class="a-module full-w-image bg-img<?php if(isset($height) && $height != ''){ echo ' '.$height; } ?>" style="background-image: url('<?php echo $image; ?>');"></div>

                <?php break;
        endswitch;

    }

    /**
    * Get Hex Value - Project Background Color
    * @return array
    */
    public function get_hex_bgc($pid = null){

        $pid = isset($pid) ? $pid : $this->post_id;
        $hex_bgc = get_field('hex_bgc', $pid);
        return $hex_bgc ?: '000000';

    }

    public function get_featured_img($pid = null){

        $pid = isset($pid) ? $pid : $this->post_id;

        /* Obtain Thumbnail Information */
        $thumb_id = get_post_thumbnail_id($pid);
        if($thumb_id){
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'work-logo', true);
            $thumb_url = $thumb_url_array[0];

            $pattern = '/default.png/';
            $is_default_img = preg_match($pattern, $thumb_url);
        }

        return (isset($thumb_url) && !$is_default_img) ? $thumb_url : false;

    }


    
} 