/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import $ from 'jquery';

import 'bootstrap';

const routes = require('../public/js/fos_js_routes.json');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$(document).ready(function(){
    $('button.js-reply-comment-btn').on('click', function (element) {
        let postId = $(this).data('post-id');
        let parentId = $(this).data('parent-id');
        if ($(this).nextAll('p.max-level-info').length === 1){
            return;
        }
        if($(this).nextAll('div.reply-comment-card').length === 0){
            $.ajax({
                url: Routing.generate('reply_comment', {post_id: postId, comment_id: parentId}),
                type: 'POST'
            }).done(function (response){
                $(element.target).after(response)
            }).fail(function (jqXHR){

            })
        }
    })
})