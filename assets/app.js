/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './scss/app.scss';

//import $ from 'jquery';

// start the Stimulus application
import './bootstrap';

$(".custom-file-input").on('change', function (e){
   var inputFile = e.currentTarget;
    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name)
});


/*$("#clickMe").click(function send(){
    console.log('clicked');
});*/

/*$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});*/

/*
console.log('hello');*/
