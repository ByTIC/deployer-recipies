<?php

/* (c) Gabriel Solomon <hello@gabrielsolomon.ro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/*
 * This recipe supports ByTic Framework 0.9
 */

namespace Deployer;

//require_once '../vendor/deployer/deployer/recipe/common.php';
//require_once '../vendor/deployer/recipes/npm.php';
//require_once '../vendor/deployer/recipes/phinx.php';
require_once __DIR__ . '/git-submodules.php';

/*** CONFIGURATION ***/
//set('ssh_type', 'native');
//set('ssh_multiplexing', true);
//set('git_cache', true);


set('keep_releases', 3);
//set('composer_command', 'composer'); // Path to composer
set('writable_use_sudo', false); // Using sudo in writable commands?

//env('composer_options',
//    'install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');
set('release_name', date('YmdHis')); // name of folder in releases


/*** SHARED FILES ***/
set('shared_files', [
    '.env',
]);

/*** WRITABLES DIRS ***/
set('writable_dirs', [
    'uploads',
    'storage/logs',
    'storage/cache',
    'storage/cache/autoloader'
]);

/*** MAIN TASK ***/
desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
//    'npm:install',
//    'phinx:migrate',
    'bytic:gitsub:npm-install',
    'bytic:gitsub:grunt',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'current',
    'success',
]);
