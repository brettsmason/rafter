<?php
$context = Timber::get_context();
$post = new Timber\Post();
$context['post'] = $post;
$templates = [ 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig', 'page.twig' ];

Timber::render( $templates, $context );
