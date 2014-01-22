Joomla Most Popular by Date Range
=================================

---------------------------
Description
---------------------------

This is an extension for the [Joomla CMS](http://www.joomla.org). It allows to display a list of the most popular articles within a specific date range.

Features
---------------------------
-   Date ranges
    -   Last day
    -   Last 7 days
    -   Last 30 days
    -   All time

-   Show articles from all categories or selected categories only

-   Show each article hit stats.

-   Include/exclude featured articles

-   Filter articles by Joomla language

Install / Configuration
---------------------------
- A packed stable version is available at [Project Homepage](http://code.joomla.org/gf/project/mostpopular_art/frs/)
- Go to your site Administration area and select "Extensions / Extension Manager" menu
Upload file and select "Upload & Install"

or

- Clone the repository into your site tmp folder and install module and plugin through Joomla Extension Manager directory option

- Enable the plugin: Extensions > Plug-in manager > "Content - Most Popular by Date Range".

- Customize module parameters in "Extensions / Module Manager" Most Popular Content by Date Range module

How does it work?
-----------------

The extension starts to track article hits from your selected categories after enabling the plugin. Any previous hits will not count in the Popular Articles list.

F.A.Q.
------

- What articles will be present on each date range?

When an article is viewed at the frontend, it automatically adds a hit to all the date ranges, so "All time" date range should include articles from all the others ranges too, "30 day range" include articles from 7 and 1 day ranges, and "7 day" includes the 1 day range.

- Why it shows an empty list beside I've installed and enabled the extension?

It can happen that no hits has been recorded after enabling the plugin. You can try to open any article at the frontend and it should start appearing on the module article list.


Changelog
--------------------------

v1.1
- Fixed bugs in article stats older than 30 days.
- Added an option to display the number of hits each articles has in module list
