<?php

namespace Deployer;

require 'recipe/common.php';
require __DIR__ . '/../common/cloudflare.php';
require __DIR__ . '/../common/npm.php';

require_once __DIR__ . '/bytic-config.php';
require_once __DIR__ . '/git-submodules.php';
require_once __DIR__ . '/bytic-commands.php';
require_once __DIR__ . '/bytic-console.php';

/*** DEFINE TASKS ***/
task(
    'deploy:storage-symlink',
    function () {
        run("cd {{deploy_path}} && {{bin/symlink}} {{release_path}}/storage/app/public current/public/uploads ");
});

task(
    'deploy:git-cache',
    function () {
        run('git config --global core.compression 0');
    }
);

/*** MAIN TASK ***/
desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'assets:install',
    'assets:build',
    'deploy:clear_paths',
    'deploy:optimize',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success',
]);

/**
 * Helper tasks
 */
desc('Optimize deployed application');
task('deploy:optimize', ['bytic:optimize']);

after('cleanup', 'assets:cleanup');

task('assets:cleanup',  function () {
    if (!has('previous_release')) {
        return;
    }
    if (!test('[ -d {{previous_release}}/node_modules ]')) {
        return;
    }

    $sudo = get('cleanup_use_sudo') ? 'sudo' : '';
    run("$sudo rm -rf {{previous_release}}/node_modules");
});

before('deploy:update_code', 'deploy:git-cache');
after('deploy:symlink', 'deploy:storage-symlink');
after('deploy:failed', 'deploy:unlock');
