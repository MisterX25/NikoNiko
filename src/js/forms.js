//Tout ou rien sélectionner ou inverser dans les checkbox
function autoSelect(form_id, value)
{
    var form = document.getElementById(form_id);
    //Boucle sur les éléments du formulaire
    for (var j = 0; j < form.elements.length; j++)
    {
        var field = form.elements[j];
        if (field.type == 'checkbox')
        {
            if (value == -1)
            {
                field.checked = !field.checked;
            }
            else
            {
                field.checked = (value == 1);
            }
        }
    }
}

//Vérification de la validité d'une date
function verifDate(d, m, y)
{
    if (m == 2 && d > 28)
    {
        if (!((y % 4 == 0 && y % 100 != 0) || y % 400 == 0))
        {
            if (d > 28)
                return false;
        }
        else
        {
            if (d > 29)
                return false;
        }
    }
    if ((m == 4 || m == 6 || m == 9 || m == 11) && d == 31)
        return false;
    return true;
}

// Vérification d'email par expression régulière
function verifEmailByReg(mail)
{
    // Motif simple
    var pattern = '^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$';
    // Motif complexe ~99.99% de la norme RFC2822
    var pattern2822 = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    var reg = new RegExp(pattern2822);
    return reg.test(mail);
}

// Vérification d'url par expression régulière
function verifUrlByReg(url)
{
    //Motif simple
    var pattern = /(((http|ftp|https):\/\/)|www\.)[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#!]*[\w\-\@?^=%&amp;/~\+#])?/;
    // Motif complet de la norme RFC3986
    var pattern3986 = /^(([^:\/?#]+):)?(\/\/([^\/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?/;
    var reg = new RegExp(pattern);
    return reg.test(url);
}

function showError (el, altmsg) // Displays the error message of the element
{
    var daddy = el.parentNode;

    msg=document.createElement('div');
    if (altmsg === undefined) // no message passed => get it from the HTML
    {
        emessage = el.getAttribute('data-errormsg');
        if (el == null)
            emessage = "Erreur";
    }
    else
        emessage = altmsg;

    msg.innerHTML=emessage;
    msg.className='formError';
    daddy.appendChild(msg);
}

function verif_form(formulaire)
{
    // remove old messages
    oldmsgs = document.getElementsByClassName('formError');
    for (var i = 0; i < oldmsgs.length; i++)
        oldmsgs[i].parentNode.removeChild(oldmsgs[i]);

    var fields = formulaire.elements;
    var valid = true;
    for (var i = 0; i < fields.length; i++)
    {
        var input = fields[i];

        //Si un champ obligatoire est vide
        if (input.value == '' && input.getAttribute('required') != null)
        {
            showError(input,'Champ obligatoire');
            input.focus();
            valid = false;
        }

        switch (input.type)
        {
            case 'email'://C'est un mail
                if (input.value != '' && !verifEmailByReg(input.value))
                {
                    showError(input);
                    input.focus();
                    valid = false;
                }
                break;


            case 'url'://C'est un lien
                if (input.value != '' && !verifUrlByReg(input.value))
                {
                    showError(input);
                    input.focus();
                    valid = false;
                }
                break;

            case 'date'://C'est une date
                valDate();
                if (!verifDate(input.value.substr(8, 2), input.value.substr(5, 2), input.value.substr(0, 4)))
                {
                    showError(input);
                    input.focus();
                    valid = false;
                }
                break;
        }

    }
    return valid;
}

function form_conf()
{
    var formulaires = document.getElementsByTagName('form');
    for (var i = 0; i < formulaires.length; i++)
    {
        theForm = formulaires[i];
        theForm.onsubmit = function ()
        {
            return verif_form(this);
        }
        if (document.getElementById('newacronym') != null)
            set_edit_mode(theForm);
        else
            set_view_mode(theForm);
    }
}

function set_view_mode(f) // puts the form in view mode
{
    var fields = f.getElementsByTagName('input');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = true;
    }
    var fields = f.getElementsByTagName('select');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = true;
    }
    // activate action buttons
    var button = document.getElementById('cmdSave');
    button.className += ' hidden';
    var button = document.getElementById('cmdDelete');
    button.className += ' hidden';
    var button = document.getElementById('cmdCancel');
    button.className += ' hidden';
    // hide edit button
    var button = document.getElementById('cmdEdit');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
}

function set_edit_mode(f) // puts the form in edit mode
{
    // enable inputs
    var fields = f.getElementsByTagName('input');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = false;
    }
    // enable selects
    var fields = f.getElementsByTagName('select');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = false;
    }
    // activate action buttons
    var button = document.getElementById('cmdSave');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    var button = document.getElementById('cmdDelete');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    var button = document.getElementById('cmdCancel');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    // hide edit button
    var button = document.getElementById('cmdEdit');
    button.className += ' hidden';
}

function set_edit_mode(f) // puts the form in edit mode
{
    // enable inputs
    var fields = f.getElementsByTagName('input');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = false;
    }
    // enable selects
    var fields = f.getElementsByTagName('select');
    for (var j = 0; j < fields.length; j++)
    {
        fields[j].disabled = false;
    }
    // activate action buttons
    var button = document.getElementById('cmdSave');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    var button = document.getElementById('cmdDelete');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    var button = document.getElementById('cmdCancel');
    button.className = button.className.replace(/\bhidden\b/,''); // remove hidden class
    // hide edit button
    var button = document.getElementById('cmdEdit');
    button.className += ' hidden';
}