<?php
$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$templates = [ 'post-' . $post->ID . '.twig', 'post-' . $post->post_type . '.twig', 'post.twig', 'page.twig' ];

Timber::render( $templates, $context );
