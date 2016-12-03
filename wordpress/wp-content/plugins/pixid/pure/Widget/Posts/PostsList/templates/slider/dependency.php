<?php

//$this is defined as a Widget class here.
//$template is defined as a \Pure\Template class here.

wp_enqueue_script('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.min.js');

wp_enqueue_style('owl-slider', $template->get_template_base_url() . '/owl-carousel/owl.carousel.css');

wp_enqueue_style('owl-slider-theme', $template->get_template_base_url() . '/owl-carousel/owl.theme.css');

wp_enqueue_script('owl-slider-bind', $template->get_template_base_url() . '/js/script.js');

wp_enqueue_style('slider-style', $template->get_template_base_url() . '/css/style.css');
