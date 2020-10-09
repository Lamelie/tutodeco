/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

const axios = require('axios/dist/axios');

let $collectionHolder;

// setup an "add a step" link
const $addStepButton = $('<button type="button" class="add_step_link btn-success">Ajouter une étape</button>');
const $newLinkLi = $('<li></li>').append($addStepButton);

$(document).ready(function() {
    // Get the ul that holds the collection of steps
    $collectionHolder = $('ul.steps');

    // add a delete link to all of the existing step form li elements
    $collectionHolder.find('li').each(function() {
        addStepFormDeleteLink($(this));
    });

    // add the "add a step" anchor and li to the steps ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addStepButton.on('click', function(e) {
        // add a new step form (see next code block)
        addStepForm($collectionHolder, $newLinkLi);
    });
});
function addStepForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    const prototype = $collectionHolder.data('prototype');

    // get the new index
    const index = $collectionHolder.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your steps field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a step" link li
    const $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addStepFormDeleteLink($newFormLi);
}

function addStepFormDeleteLink($stepFormLi) {
    const $removeFormButton = $('<button type="button">Supprimer cette étape</button>');
    $stepFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the step form
        $stepFormLi.remove();
    });
}

$(document).ready(function() {
    const $tutos = $('.card-tuto-list-image');

    $tutos.on('mouseenter', function () {
        const id = $(this).attr("id");
        $('#seemore-button-' + id).show()

    });

    $tutos.on('mouseleave', function () {
        const id = $(this).attr("id");
        $('#seemore-button-' + id).hide()
    });
})

//TODO : mettre le select2 pour les tags.

$(document).ready(function () {

    function onClickBtnDone(event) {
        event.preventDefault();

        const url = this.href;
        const nbDone = $('.js-nbDone', this)
        const icone = $('i', this)
        console.log(icone);

        axios.get(url).then(function (response) {
            const dones = response.data.dones;
            nbDone.text(dones);

            if(icone.hasClass('fas')){
                icone.removeClass('fas').addClass('far')
            } else {
                icone.removeClass('far').addClass('fas')
            }
        })
    }
    const $doneLinks = $('a.js-done-link')
    $doneLinks.on('click', onClickBtnDone);
})

$(document).ready(function () {

    function onClickBtnTodo(event) {
        event.preventDefault();

        const url = this.href;
        const nbTodo = $('.js-nbTodo', this)
        const icone = $('i', this)
        console.log(icone);

        axios.get(url).then(function (response) {
            const todos = response.data.todos;
            nbTodo.text(todos);

            if(icone.hasClass('fas')){
                icone.removeClass('fas fa-clipboard-list').addClass('far fa-clipboard')
            } else {
                icone.removeClass('far fa-clipboard').addClass('fas fa-clipboard-list')
            }
        })
    }
    const $todoLinks = $('a.js-todo-link')
    $todoLinks.on('click', onClickBtnTodo);
})

$(function () {
    $('[data-toggle="popover"]').popover()
})
