<?php
$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$context['title'] = bloom_get_archive_title();
$context['description'] = term_description();
$templates = [ 'archive.twig', 'index.twig' ];

Timber::render( $templates, $context );
