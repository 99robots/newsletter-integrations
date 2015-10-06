<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

do_action('nnr_news_int_before_autoload_v1');

require_once('controllers/settings.php');
require_once('controllers/submission.php');
require_once('controllers/table.php');
require_once('views/form.php');

do_action('nnr_news_int_after_autoload_v1');