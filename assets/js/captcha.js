/**
 * Fonction de callback exécutée une fois que les google dependenciees sont chargées.
 */
function onGoogleReCaptchaApiLoad() {
    const widgets = document.querySelectorAll('[data-toggle="recaptcha"]');
    for (let i = 0; i < widgets.length; i++) {
        renderReCaptcha(widgets[i]);
    }
}

window.onGoogleReCaptchaApiLoad = onGoogleReCaptchaApiLoad;

function renderReCaptcha(widget) {
    const form = widget.closest('form');
    const widgetType = widget.getAttribute('data-type');
    const widgetParameters = {
        'sitekey': '{{ gg_recaptcha_site_key }}'
    };

    if (widgetType == 'invisible') {
        widgetParameters['callback'] = function () {
            form.submit()
        };
        widgetParameters['size'] = "invisible";
    }

    const widgetId = grecaptcha.render(widget, widgetParameters);

    if (widgetType == 'invisible') {
        bindChallengeToSubmitButtons(form, widgetId);
    }
}

/**
 * Empeche le bouton de soumettre le formulaire
 */
function bindChallengeToSubmitButtons(form, reCaptchaId) {
    getSubmitButtons(form).forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            grecaptcha.execute(reCaptchaId);
        });
    });
}

/**
 * Trouver les boutons pour le formulaire
 */
function getSubmitButtons(form) {
    const buttons = form.querySelectorAll('button, input');
    const submitButtons = [];

    for (let i= 0; i < buttons.length; i++) {
        const button = buttons[i];
        if (button.getAttribute('type') == 'submit') {
            submitButtons.push(button);
        }
    }

    return submitButtons;
}
