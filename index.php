<?php
$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();
$templates = array( 'index.twig' );

Timber::render( $templates, $context );
