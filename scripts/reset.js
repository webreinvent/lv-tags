let log = require('color-console');
let fs = require('fs');
let path = require('path');
let fsSync = require('fs-sync');
let fsExtra = require('fs-extra')


let remove_list = {
    'folders': [
        'config',
        'lang',
        'database',
        'src',
        'views',
    ],
    'files': [
        'routes.php',
        'README.md',
        'config.json',
        'composer.json'
    ]
};

log.red("Following folders and files are deleted:");
log.red("=============================================================================");

remove_list.folders.forEach(function(item) {
    fsExtra.removeSync(item);
    log.red(item);
});

remove_list.files.forEach(function(item) {
    fsExtra.removeSync(item);
    log.red(item);
});


