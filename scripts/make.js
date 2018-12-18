let log = require('color-console');
let readlineSync = require('readline-sync');
let fs = require('fs');
let path = require('path');
let fsSync = require('fs-sync');
let ejs = require('ejs');
var dateFormat = require('dateformat');

var argv = require('minimist')(process.argv.slice(2));
console.dir(argv);


/*
|--------------------------------------------------------------------------
| Get Package Configuration
|--------------------------------------------------------------------------
*/
let package_config = {};


let config_file = './config.json';
//check config files
if (!fs.existsSync(config_file)) {
    log.red("'"+config_file+"' file does not exist.");
    log.red("You must run 'npm run start' to generate the package and config.json file");
    return false;
}

file_content = fs.readFileSync(config_file).toString();

package_config = JSON.parse(file_content);

let args = process.argv;

let maker = args[2];
let plain = args[3];

log.yellow('test='+plain);

if(!maker)
{
    log.red("'npm run make [command]:[name]'");
    return false;
}

let agr = maker.split(":");

let type = agr[0];
let name = agr[1];;

package_config.type = type;
package_config.name = name;

if(!type)
{
    log.red("'npm run make [command]:[name]'");
    return false;
}


log.green('Command='+type+" | Name="+name);
log.green("=============================================================================");

let file_name = null;
let des_path = null;
let table_name = null;
let now = new Date();

switch (type) {
    case 'model':
        file_content = fs.readFileSync('./templates/model.ejs').toString();
        file_content = ejs.render(file_content, package_config);
        file_name = package_config.name+'.php';
        des_path = './src/'+file_name;
        break;
    case 'view':
        file_content = fs.readFileSync('./templates/view.ejs').toString();
        file_content = ejs.render(file_content, package_config);
        file_name = package_config.name+'.blade.php';
        des_path = './views/'+file_name;
        break;
    case 'controller':
        file_content = fs.readFileSync('./templates/controller.ejs').toString();
        if(plain)
        {
            file_content = fs.readFileSync('./templates/controller-plain.ejs').toString();
        }
        file_content = ejs.render(file_content, package_config);
        file_name = package_config.name+'Controller.php';
        des_path = './src/'+file_name;
        break;
    case 'seed':
        file_content = fs.readFileSync('./templates/seed.ejs').toString();
        file_content = ejs.render(file_content, package_config);
        file_name = package_config.name+'TableSeeder.php';
        des_path = './database/seeds/'+file_name;
        break;
    case 'migration':

        table_name = package_config.name;
        table_name = table_name.replace("_", " ");
        table_name = titleCase(table_name);
        table_name = table_name.replace(" ", "");
        package_config.class_name = table_name;

        file_content = fs.readFileSync('./templates/migration.ejs').toString();

        log.red('class_name='+package_config.class_name);

        file_content = ejs.render(file_content, package_config);
        file_name = dateFormat(now, "yyyy_mm_dd_HHMMss_")+package_config.name+'_table.php';
        des_path = './database/migrations/'+file_name;

        break;

    default:
        log.red('Sorry, "'+type+'" command does not match with any of the available commands.');
}


log.green("Following file is generated:");
log.green("=============================================================================");
log.green(des_path);

fsSync.write(des_path, file_content);


function titleCase(str) {
    var wordsArray = str.toLowerCase().split(/\s+/);
    var upperCased = wordsArray.map(function(word) {
        return word.charAt(0).toUpperCase() + word.substr(1);
    });
    return upperCased.join(" ");
}
