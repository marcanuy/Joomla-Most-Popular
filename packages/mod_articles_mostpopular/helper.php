<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_mostpopular
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

/**
 * Helper for mod_articles_mostpopular
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_mostpopular
 */
abstract class modArticlesMostpopularHelper
{
  public static function getList(&$params)
  {
    // Create a new query object.
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
		
		
    // Select the required fields from the table.
    $query->select('a.id, a.title, a.alias, a.introtext, a.catid, p.1_day_stats, p.7_day_stats, p.30_day_stats, p.all_time_stats');
    $query->from('#__content AS a');
    $query->join('INNER','#__mostpopular AS p ON a.id=p.content_id');

    // Join over the categories.
    $query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias');
    $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

    // Filter by a single or group of categories (It does not include subcategories)
    $categoryId = $params->get('catid', array());

    if (is_numeric($categoryId)) {
      $categoryEquals = 'a.catid ='.(int) $categoryId;
      $query->where($categoryEquals);
    }
    elseif (is_array($categoryId) && (count($categoryId) > 0)) {
      JArrayHelper::toInteger($categoryId);
      $categoryId = implode(',', $categoryId);
      if (!empty($categoryId)) {
	$query->where('a.catid IN'.' ('.$categoryId.')');
      }
    }

    // Filter by access level
    $user	= JFactory::getUser();
    $groups	= implode(',', $user->getAuthorisedViewLevels());
    $query->where('a.access IN ('.$groups.')');
    $query->where('c.access IN ('.$groups.')');
		
    // Filter by published state
    $published = 1;

    // Filter by featured state
    $featured = $params->get('show_front', 1) == 1 ? 'show' : 'hide';
    switch ($featured)
      {
      case 'hide':
	$query->where('a.featured = 0');
	break;

      case 'only':
	$query->where('a.featured = 1');
	break;

      case 'show':
      default:
	// Normally we do not discriminate
	// between featured/unfeatured items.
	break;
      }

      // Filter by start and end dates.
    $nullDate	= $db->Quote($db->getNullDate());
    $nowDate	= $db->Quote(JFactory::getDate()->toSql());

    $query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')');
    $query->where('(a.publish_down = '.$nullDate.' OR a.publish_down >= '.$nowDate.')');

    // Filter by language
    $languageFilter = $params->get('language_filter', 1);
    if ($languageFilter == 1) {
      $query->where('a.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
      /* @todo filter by contact language */
    }
		
    // Filter by stats module
    $orderingRange = $params->get('ordering_range', 0);
    switch ($orderingRange)
      {
		
      case 1:
	$query->order('p.1_day_stats DESC');
	break;
      case 7:
	$query->order('p.7_day_stats DESC');
	break;
      case 30:
	$query->order('p.30_day_stats DESC');
	break;
      case 0:
      default:
	$query->order('p.all_time_stats DESC');
	break;
      }

    $articlesCount = $params->get('count', 5);		
    $db->setQuery($query,0,$articlesCount);
    $items = $db->loadObjectList();

    // Access filter
    $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
    $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		
    foreach ($items as &$item) {
      $item->slug = $item->id.':'.$item->alias;
      $item->catslug = $item->catid.':'.$item->category_alias;

      if ($access || in_array($item->access, $authorised)) {
	// We know that user has the privilege to view the article
	$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
      } else {
	$item->link = JRoute::_('index.php?option=com_users&view=login');
      }
    }

    return $items;
  }
	
}
