let log = require('color-console');
let readlineSync = require('readline-sync');
let fs = require('fs');
let path = require('path');
let fsSync = require('fs-sync');
let ejs = require('ejs');


/*
|--------------------------------------------------------------------------
| Get Package Configuration
|--------------------------------------------------------------------------
*/
let package_obj = {};
let default_input = {
    defaultInput: ''
};

let vendor_name = readlineSync.question('What is your vendor name? E.g. WebReinvent: ');
package_obj.vendor_name = vendor_name;
package_obj.vendor_name_lower = vendor_name.toLowerCase();

let package_name = readlineSync.question('What is your package name? E.g. LvTags: ');
package_obj.package_name = package_name;
package_obj.package_name_lower = package_name.toLowerCase();

package_obj.namespace = package_obj.vendor_name+'\\'+package_obj.package_name;

package_obj.description = readlineSync.question('What is your description? ', default_input);

package_obj.homepage = readlineSync.question('What is your homepage url? ', default_input);

package_obj.author_name = readlineSync.question('What is author name? ', default_input);

package_obj.author_email = readlineSync.question('What is author email? ', default_input);

/*
|--------------------------------------------------------------------------
| Generate a config.json file for future usages
|--------------------------------------------------------------------------
*/
fsSync.write('./config.json', JSON.stringify(package_obj));


/*
|--------------------------------------------------------------------------
| Generate package files
|--------------------------------------------------------------------------
*/
let template_path = './templates/package/';

let files_list = [];
files_list = traverseDir(template_path, files_list);

let file_name;
let file_content;
let new_path;
let des_path;

log.green('Package Name='+package_obj.package_name+" | Namespace="+package_obj.namespace);
log.green("Following files are generated:");
log.green("=============================================================================");


files_list.forEach(function(item) {


    file_content = fs.readFileSync(item).toString();

    file_name = path.basename(item);
    new_path = item.replace('templates', '');
    new_path = new_path.replace('package', '');
    new_path = new_path.replace(/\\/,'');
    new_path = new_path.replace(/\\/,'');


    new_path = new_path.replace(file_name,'');



    switch(file_name) {
        case 'packagename.php':
            file_name = package_obj.package_name_lower+'.php';
            break;
        case 'ServiceProvider.ejs':
            file_content = fs.readFileSync(item).toString();
            file_content = ejs.render(file_content, package_obj);
            file_name = package_obj.package_name+file_name+'.php';
            break;
        case 'composer.ejs':
            file_content = fs.readFileSync(item).toString();
            file_content = ejs.render(file_content, package_obj);
            file_name = 'composer.json';
            break;
    }

    des_path = new_path+file_name;

    log.green(des_path);

    fsSync.write(des_path, file_content);

});



/*
|--------------------------------------------------------------------------
| Get list of files
|--------------------------------------------------------------------------
*/
function traverseDir(dir, files_list){
    fs.readdirSync(dir).forEach(file => {
        let fullPath = path.join(dir, file);
        if (fs.lstatSync(fullPath).isDirectory()) {
            traverseDir(fullPath, files_list);
        } else {
            files_list.push(fullPath);
        }
    });
    return files_list;
}
