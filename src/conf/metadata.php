<?php
$meta['min_query_length'] = array('numeric', '_min' => 0);
$meta['use_on_actions'] = array('multicheckbox',
    '_choices' => ['backlink', 'conflict', 'diff', 'draft', 'edit', 'index', 'media', 'preview', 'recent', 'revisions', 'save', 'search', 'show'],
    '_other' => 'never');
