/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
require('bootstrap');
//require('./captcha');

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

const axios = require('axios/dist/axios');

let $collectionHolder;

// ajoute un lien "ajouter une étape"
const $addStepButton = $('<button type="button" class="btn tutodeco-btn">Ajouter une étape</button>');
const $newLinkLi = $('<li></li>').append($addStepButton);

$(document).ready(function() {
    // Récupère la collection de "steps"
    $collectionHolder = $('ul.steps');

    // ajoute un lien "supprimer" aux étapes existantes
    $collectionHolder.find('li').each(function() {
        addStepFormDeleteLink($(this));
    });

    // ajoute un bouton "ajouter un étape" après les étapes existantes
    $collectionHolder.append($newLinkLi);

    // compte le nombre d'étapes et ajoute ce nombre à la data "index"
    $collectionHolder.data('index', $collectionHolder.find('textarea').length);

    $addStepButton.on('click', function(e) {
        // ajoute une nouvelle étape
        addStepForm($collectionHolder, $newLinkLi);
    });
});

//fonction ajouter une étape.
function addStepForm($collectionHolder, $newLinkLi) {
    const prototype = $collectionHolder.data('prototype');

    const index = $collectionHolder.data('index');

    let newForm = prototype;

    // remplace '__name__' dans le prototype HTML
    // au lieu d'avoir le numéro de l'index
    newForm = newForm.replace(/__name__/g, index);

    // augmente l'index de un pour la prochaine étape
    $collectionHolder.data('index', index + 1);

    // affiche le formulaire de création d'étape avant le bouton "ajouter une étape"
    const $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // ajoute le numéro de l'étape que l'on vient d'ajouter
    $newFormLi.find('input[name="tutorial[steps][' + index + '][number]"]').val(index + 1);

    addStepFormDeleteLink($newFormLi);
}
//permet la suppression d'une étape
function addStepFormDeleteLink($stepFormLi) {
    const $removeFormButton = $('<button type="button" class="btn tutodeco-btn-supp">Supprimer cette étape</button>');
    $stepFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        $stepFormLi.remove();
    });
}

//permet l'affichage du bouton "see more" au survol de l'image.
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


//permet l'ajout en liste des tutoriels réalisés
$(document).ready(function () {

    function onClickBtnDone(event) {
        event.preventDefault();

        const url = this.href;
        const nbDone = $('.js-nbDone', this)
        const icone = $('i', this)
        console.log(icone);

        //récupère les données envoyées en Json au clic sur le bouton.
        axios.get(url).then(function (response) {
            const dones = response.data.dones;
            //modifie le texte présent dans "nbDone"
            nbDone.text(dones);

            //change les classes des icônes
            if(icone.hasClass('fas')){
                icone.removeClass('fas').addClass('far')
            } else {
                icone.removeClass('far').addClass('fas')
            }
            //renvoie une erreur http et modifie l'url en cas d'erreur
        }).catch(function (error) {
            if (error.response.status === 403) {
                window.location.href = '/login'
            }
        })
    }
    const $doneLinks = $('a.js-done-link')
    $doneLinks.on('click', onClickBtnDone);
})


//permet l'ajout en todolist
$(document).ready(function () {

    function onClickBtnTodo(event) {
        event.preventDefault();

        const url = this.href;
        const nbTodo = $('.js-nbTodo', this)
        const icone = $('i', this)

        //récupère les données envoyées en Json au clic sur le bouton.
        axios.get(url).then(function (response) {
            //récupère le nombre de todos
            const todos = response.data.todos;
            //affiche le nombre de todos
            nbTodo.text(todos);

            //remplace les icones vides/pleines en fonction de l'ajout ou suppression
            if(icone.hasClass('fas')){
                icone.removeClass('fas fa-clipboard-list').addClass('far fa-clipboard')
            } else {
                icone.removeClass('far fa-clipboard').addClass('fas fa-clipboard-list')
            }
        }).catch(function (error) {
            if(error.response.status === 403) {
                window.location.href = '/login'
            }
        })
    }
    //applique la fonction au clic sur l'icone.
    const $todoLinks = $('a.js-todo-link')
    $todoLinks.on('click', onClickBtnTodo);
})

//faire apparaitre le nom du fichier dans l'input (form)

const input = $('.custom-file-input')


input.on('change', function(event) {
    const inputFile = event.currentTarget;
    //si pas de fichier dans l'input
    if(inputFile.files.length===0){
        //trouve le parent de l'input
        $(inputFile).parent()
            //trouve le label
            .find('.custom-file-label')
            .html("Selectionner une image");
    }else {
        //si un fichier téléchargé
        $(inputFile).parent()
            .find('.custom-file-label')
            //remplace le placeholder par le nom du fichier
            .html(inputFile.files[inputFile.files.length-1].name);
    }
});




