<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_popular
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$list = modArticlesMostpopularHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$show_hits = $params->get('show_hits', 0);
$ordering_range = $params->get('ordering_range', 0);

require JModuleHelper::getLayoutPath('mod_articles_mostpopular', $params->get('layout', 'default'));
