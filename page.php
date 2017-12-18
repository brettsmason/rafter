<?php
$context = Timber::get_context();
$post = new Timber\Post();
$context['post'] = $post;
$templates = [ 'page-' . $post->ID . '.twig', 'page-' . $post->post_name . '.twig', 'page.twig' ];

Timber::render( $templates, $context );
