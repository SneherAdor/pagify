let mix = require('laravel-mix');

// Path for your package source files
let resourcePath = 'public'; // Source path to package files
let outputPath = 'public/pagify'; // Output path to public directory

// Concatenate JS files
mix.js(`${resourcePath}/js/app.js`, `${outputPath}/js`)
   .scripts([
     `${resourcePath}/pagify/js/bootstrap.min.js`,
     `${resourcePath}/pagify/js/jquery.min.js`,
     `${resourcePath}/js/select2.min.js`,
     `${resourcePath}/js/jquery.mCustomScrollbar.concat.min.js`,
     `${resourcePath}/js/jquery-confirm.min.js`,
     `${resourcePath}/js/popper-core.js`,
     `${resourcePath}/js/tippy.js`,
     `${resourcePath}/js/flatpickr.js`,
     `${resourcePath}/js/jquery.colorpicker.bygiro.js`,
     `${resourcePath}/js/summernote-lite.min.js`,
     `${resourcePath}/js/nouislider.min.js`,
     `${resourcePath}/js/optionbuilder.js`,
     `${resourcePath}/js/Sortable.min.js`,
     `${resourcePath}/js/iconPicker.js`,
   ], `${outputPath}/js/all.js`); // All JS concatenated into 'all.js'

// Concatenate CSS files
mix.sass(`${resourcePath}/sass/app.scss`, `${outputPath}/css`)
   .styles([
     `${resourcePath}/pagify/css/bootstrap.min.css`,
     `${resourcePath}/css/select2.min.css`,
     `${resourcePath}/css/jquery.mCustomScrollbar.min.css`,
     `${resourcePath}/css/feather-icons.css`,
     `${resourcePath}/css/jquery-confirm.min.css`,
     `${resourcePath}/css/flatpickr.min.css`,
     `${resourcePath}/css/jquery.colorpicker.bygiro.css`,
     `${resourcePath}/css/summernote-lite.min.css`,
     `${resourcePath}/css/nouislider.min.css`,
     `${resourcePath}/css/pagify.css`,
     `${resourcePath}/css/iconPicker.css`,
   ], `${outputPath}/css/all.css`); // All CSS concatenated into 'all.css'
