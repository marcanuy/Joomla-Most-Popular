<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_mostpopular
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="mostread<?php echo $moduleclass_sfx; ?>">
  <?php foreach ($list as $item) : ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<?php echo $item->title; ?></a>
                <?php if($show_hits == "1") : ?>
                <?php    $hits = 0; ?>
                <?php    switch ($ordering_range)
                           {
                           case 1:
                              $hits = $item->{'1_day_stats'};
                              break;
                           case 7:
                              $hits = $item->{'7_day_stats'};
                              break;
                           case 30:
                              $hits = $item->{'30_day_stats'};
                              break;
                           case 0:
                           default:
                              $hits = $item->all_time_stats;
                           }
                ?>
                <?php echo " - ".$hits." ".JText::_("MOD_ARTICLES_MOSTPOPULAR_HITS"); ?>
                <?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
