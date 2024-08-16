const node_modules = process.env['SCSS_COMPILER_NODE_MODULES_PATH'];
const drupal_root = process.env['SCSS_COMPILER_DRUPAL_ROOT'];
const cache_folder = process.env['SCSS_COMPILER_CACHE_FOLDER'];

if (!node_modules || !drupal_root || !cache_folder) {
  return;
}

const fs = require('fs');
const sass = require(node_modules + '/node-sass');

let data = fs.readFileSync(cache_folder + '/libsass_temp.json', { encoding: 'utf-8' });
data = JSON.parse(data);

const config = data.config;
const files = data.files;

files.forEach((file) => {
  sass.render({
    file: drupal_root + '/' + file.source_path,
    outFile: file.css_path,
    sourceMap:  config.sourcemaps,
    outputStyle: config.output_format,
    includePaths: config.import_paths,
    functions: {
      'url($img)': function (img) {
        let value = img.getValue();
        if (['https://', 'http://', '//', 'data:'].some(v => value.startsWith(v))) {
          return new sass.types.String('url("' + value + '")');
        }
        else {
          return new sass.types.String('url("' + file.assets_path + value + '")');
        }
      }
    },
  }, (err, result) => {
    if (err) {
      throw err;
    }
    fs.writeFile(drupal_root + '/' + file.css_path, result.css, (err) => {
      if (err) {
        throw err;
      }
    });
    if (result.map) {
      fs.writeFile(drupal_root + '/' + file.css_path + '.map', result.map, (err) => {
        if (err) {
          throw err;
        }
      });
    }
  });
});
