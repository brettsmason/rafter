<?php
$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['title'] = sprintf( __( 'Search Results for: %s', 'rafter' ), '<strong>' . get_search_query() . '</strong>' );
$templates = [ 'search.twig', 'archive.twig', 'index.twig' ];

Timber::render( $templates, $context );
