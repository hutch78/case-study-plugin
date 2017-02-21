# Modular Case Study Class for a WordPress Theme

This project contains files related to a heavily customizable, modular, content managed case study plugin I built. The case study module generates and outputs case studies when the page template is requested. it also outputs css data containers used by script.js to custom-color elements such as svg fill colors, and css background colors to match the color theme of the case study. This project was designed by <a href="http://oatemeal.co">John</a> but built from scratch with love by [me](http://jhutchcraft.com)

## @ToDo:

* move this all into the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) for proper use as a WordPress plugin

## File Contents

 * **case_study_module.php**- this file is the class i used primarily to organize the serverside code that would be used for the case studies.
 * **script.js** - this is the javascript that I used to custom color elements in the case study template. It reads css data containers created by the case study module, ie:
 ```html
<i class="hex-box" id="socials-color" data-hex="<?php echo $this->get_hex_bgc(); ?>"></i>
```
... and then colors elements with special triggers on them
 * **case_study_admin.png** - this is a screenshot of the back end of the case study page template. I used Advanced Custom Fields to create the custom fields in the admin page.
 * **page_template.php** - this is the page template i used to render case studies. It uses the loop to get a post id, then passes the post ID into an instance of the case study module, then renders the project.
