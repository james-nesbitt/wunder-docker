<?php
/**
* Drush Aliases
*
* These are remote aliases designed to let you
* connect with remote environments directly
* using drush.
*
* DEVELOPER: change these to match your remote
*   environments accordinagly.  Match the uri,
*   root, remote-user and remote-hosts; the rest
*   is somewhat generic.
*
* TO USE:
*   $/> drush @prod.dev cc all
*   $/> drush @prod.prod uli
*
* OTHER OPTIONS:
*     'ssh-options' => '-i /home/developer/.ssh-host/id_rsa'
*/

$sites = array(
  'local' => array(
    'uri' => 'project.local',
    'root' => '/app/www',
    'databases' => array (
      'default' => array (
        'default' => array (
          'driver' => 'mysql',
          'host' => 'database.app',
          'database' => 'project',
          'username' => 'project',
          'password' => 'project',
        ),
      ),
    ),
    'command-specific' => array(
      'site-install' => array(
        'account-name' => 'project',
        'account-pass' => 'project',
        'account-mail' => 'project@project.test.com',
        'site-name' => 'project',
        'site-mail' => 'project@project.test.com',
        'yes' => FALSE,
      ),
    ),
  ),

//   'dev' => array(
//     'uri' => 'project.dev.wunder.io',
//     'root' => '/var/www/project',
//
//     'remote-host' => 'project.dev.wunder.io',
//     'remote-user' => 'www',
//   ),
//   'stage' => array(
//     'uri' => 'project.stage.wunder.io',
//     'root' => '/var/www/project',
//
//     'remote-host' => 'project.stage.wunder.io',
//     'remote-user' => 'www',
//   ),
//   'prod' => array(
//     'uri' => 'project.prod.wunder.io',
//     'root' => '/var/www/project',
//
//     'remote-host' => 'project.prod.wunder.io',
//     'remote-user' => 'www',
//   ),
);

$global_config = array(
  'path-aliases' => array(
    '%public' => 'sites/default/files/public',
    '%private' => 'sites/default/files/private',
    '%dump-dir' => '/tmp'
  ),

  'command-specific' => array(
    'sql-sync' => array(
      'no-cache' => TRUE,
      'no-ordered-dump' => TRUE,
      'structure-tables' => array(
        'common' => array('cache','cache_filter','cache_menu','cache_page','cache_views_data','history','sessions','search_index','sessions','watchdog'), // <-- ignore these tables when using sql-sync
      ),
    ),
    'sql-dump' => array(
      'structure-tables' => array(
        'common' => array('cache','cache_filter','cache_menu','cache_page','cache_views_data','history','sessions','search_index','sessions','watchdog'), // <-- ignore these tables when dumping the DB
      ),
    ),
    'rsync' => array (
      'mode' => 'rlptDz',
      'exclude-paths' => 'ctools:advagg_css:advagg_js:css:imagecache:ctools:js:styles:tmp:xmlsitemap', // <-- ignore these paths when running rsync
    ),
  ),
);

foreach($sites as $key=>$site) {
  $aliases[$key] = array_merge_recursive($global_config, $site);
}
