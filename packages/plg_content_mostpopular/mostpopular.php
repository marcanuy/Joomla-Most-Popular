<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.mostpopular
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Most Popular Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.mostpopular
 */
class plgContentMostpopular extends JPlugin
{

  	public function onContentAfterDisplay( $context, &$article, &$params, $page = 0 )
	{
	  // only compute frontend views
	  if( JFactory::getApplication()->isSite() &&
	      $context == "com_content.article" )
	    {
	      $jinput = JFactory::getApplication()->input;
	      $view = $jinput->get('view', '', 'STRING');
	      // avoid computing category views
	      if ( $view == 'article' ) {
		$this->_updateStats($article);
	      }
	    }
	  return '';
	}


	private function _updateStats(&$article) {

	  if ( $article->id ) {
	    // Get the existing raw stats
	    $raw_stats = $this->_getRawStats($article);
	    $date = gmdate('Y-m-d');

	    if ( $raw_stats ) {
	      $raw_stats = unserialize( $raw_stats );
	    } else {
	      // Create an entry for this post
	      $this->_insertArticleStats($article);
	    }

	    $count_1 = $this->_calculate_1_day_stats( $raw_stats, $date );
	    $days = 7;
	    $count_7 = $this->_calculate_days_stats( $days, $raw_stats, $date );
	    $days = 30;
	    $count_30 = $this->_calculate_days_stats( $days, $raw_stats, $date );

	    if ( isset( $raw_stats ) && count( $raw_stats ) >= 30 ) {
	      array_shift( $raw_stats );
	      $raw_stats[$date] = 1;
	    } else {
	      if ( ! isset( $raw_stats[$date] ) ) {
		$raw_stats[$date] = 1;
	      } else {
		$raw_stats[$date]++;
	      }
	    }

	    // Update table with new stats
	    $db = JFactory::getDbo();
	    $query = $db->getQuery(true);
	    // Count the items in this category
	    $query->update('#__mostpopular');
	    $query->set('1_day_stats = '.$count_1);
	    $query->set('7_day_stats = '.$count_7);
	    $query->set('30_day_stats = '.$count_30);
	    $query->set('all_time_stats = all_time_stats + 1');
	    $query->set('raw_stats = '.$db->quote(serialize($raw_stats)));
	    $query->where('content_id = '.$article->id);
	    $db->setQuery($query);

	    try
	      {
		$insertArticle = $db->query();
	      }
	    catch (RuntimeException $e)
	      {
		JError::raiseWarning(500, $e->getMessage());
		return false;
	      }

	  }
	}

	private function _calculate_1_day_stats( $existing_stats, $date ) {
	  if ( $existing_stats ) {
	    if ( isset( $existing_stats[$date] ) ) {
	      return $existing_stats[$date] + 1;
	    }
	  }
	  return 1;
	}

	private function _calculate_days_stats( $days, $existing_stats, $date ) {
	  if ( $existing_stats ) {
	    $extra_to_add = 0;
	    if ( isset( $existing_stats[$date] ) ) {
	      $extra_to_add = $existing_stats[$date];
	    }
	    $total = 0;
	    for ( $i = 1; $i < $days; $i++ ) {
	      $old_date = date('Y-m-d', strtotime( "-{$i} days" ) );
	      if ( isset( $existing_stats[$old_date] ) ) {
		$total += $existing_stats[$old_date];
	      }
	    }
	    return $total + $extra_to_add + 1;
	  }
	  return 1;
	}

	private function _getRawStats(&$article)
	{
	  $db = JFactory::getDbo();
	  $query = $db->getQuery(true);
	  // Count the items in this category
	  $query->select('raw_stats');
	  $query->from('#__mostpopular');
	  $query->where('content_id = ' . $article->id);
	  $db->setQuery($query);

	  try
	    {
	      $rawStats = $db->loadResult();
	    }
	  catch (RuntimeException $e)
	    {
	      JError::raiseWarning(500, $e->getMessage());
	      return false;
	    }

	  return $rawStats;
	}

	private function _insertArticleStats($article)
	{
	  $db = JFactory::getDbo();
	  $query = $db->getQuery(true);
	  // Count the items in this category
	  $query->insert('#__mostpopular');
	  $query->columns('content_id, last_updated, 1_day_stats, 7_day_stats, 30_day_stats, all_time_stats, raw_stats');
	  $query->values($article->id.',NOW(),"0","0","0","0",""');
	  $db->setQuery($query);

	  try
	    {
	      $insertArticle = $db->query();
	    }
	  catch (RuntimeException $e)
	    {
	      JError::raiseWarning(500, $e->getMessage());
	      return false;
	    }

	  return $insertArticle;
	}

}
