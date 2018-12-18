let log = require('color-console');
let readlineSync = require('readline-sync');
let fs = require('fs');
let path = require('path');
let fsSync = require('fs-sync');
let ejs = require('ejs');

let item = './scripts/templates/package/README.md';
file_content = fs.readFileSync(item).toString();
let des_path = './README.md';
fsSync.write(des_path, file_content);

log.green('README.md file copied');
