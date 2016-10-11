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
    if (y < 1900 || y > 2100) return false;

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
    var reg = new RegExp(pattern);
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

function showError(el, altmsg) // Displays the error message of the element
{
    var daddy = el.parentNode;

    entry = document.createElement('div');
    if (altmsg === undefined) // no message passed => get it from the HTML
    {
        emessage = el.getAttribute('data-errormsg');
        if (el == null)
            emessage = "Erreur";
    }
    else
        emessage = altmsg;

    entry.innerHTML = emessage;
    entry.className = 'formError';
    daddy.appendChild(entry);
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
            showError(input, 'Champ obligatoire');
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

            case 'url'://C'est un lien   !!!!!! As of Sept 2016, Safari does not recognize this type: it sees a text instead
                if (input.value != '' && !verifUrlByReg(input.value))
                {
                    showError(input);
                    input.focus();
                    valid = false;
                }
                break;

            case 'date'://C'est une date    !!!!!! As of Sept 2016, Safari does not recognize this type: it sees a text instead
                if (!verifDate(input.value.substr(0, 2), input.value.substr(3, 2), input.value.substr(6, 4)))
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
        };

        // Put the form in the appropriate mode
        fmode = theForm.getAttribute('data-formmode');
        switch (fmode)
        {
            case 'editprofile':
                set_edit_mode(theForm);
                break;
            case 'viewprofile':
                set_view_mode(theForm);
                break;
            case 'newprofile':
                set_create_mode(theForm);
                break;
        }
    }
}

function showButtons(buttonList)
{
    for (i = 0; i < buttonList.length; i++)
    {
        var button = document.getElementById(buttonList[i]);
        button.className = button.className.replace(/\bhidden\b/, ''); // remove hidden class
    }
}

function hideButtons(buttonList)
{
    for (i = 0; i < buttonList.length; i++)
    {
        var button = document.getElementById(buttonList[i]);
        button.className += ' hidden'; // add hidden class
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
    // Hide/Show appropriate buttons
    showButtons(['cmdEdit']);
    hideButtons(['cmdDelete', 'cmdCancel', 'cmdSave', 'cmdCreate', 'inpAcronym'])
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
    // Hide/Show appropriate buttons
    showButtons(['cmdSave', 'cmdDelete', 'cmdCancel']);
    hideButtons(['cmdCreate', 'cmdEdit', 'inpAcronym'])

    document.getElementById("email").addEventListener("keyup", checkAvailability);
}

function set_create_mode(f) // puts the form in edit mode
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
    // Hide/Show appropriate buttons
    showButtons(['cmdCreate', 'cmdCancel', 'inpAcronym']);
    hideButtons(['cmdSave', 'cmdDelete', 'cmdEdit']);

    // Add event handler for acronym validation
    document.getElementById("acronym").addEventListener("keyup", checkAvailability);
    document.getElementById("email").addEventListener("keyup", checkAvailability);
}

function checkAvailability(e)
{
    field = e.target; // the field: either mail or acronym

    // Remove previous validation messages - if any
    oldmsgid = field.id+'entry';
    var oldmsg = document.getElementById(oldmsgid);
    if (oldmsg != null) oldmsg.parentNode.removeChild(oldmsg);

    val = field.value.toUpperCase();
    // assess if input is complete
    switch (field.id)
    {
        case 'acronym': inputcomplete = (val.length == 3); break;
        case 'email': inputcomplete = verifEmailByReg(val); break;
    }

    if (inputcomplete) // let's check it with the server using ajax
    {
        // test it with the server using ajax
        params = field.id + '=' + val;
        var rq = new XMLHttpRequest();
        rq.open("POST", "src/ajax/checkavailability.php", true); // true is for async mode
        rq.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // indicate recipient that parameters are in the url

        rq.onreadystatechange = function ()
        {
            if (rq.readyState == XMLHttpRequest.DONE)
                if (rq.status != 200)
                    console.log('Error validating field: '+field.id);
                else
                {
                    // prepare message to add
                    entry = document.createElement('div');
                    entry.id = field.id+'entry';
                    if (rq.responseText == "Ok")
                    {
                        entry.innerHTML = "Disponible";
                        entry.className = 'formInfo';
                    }
                    else
                    {
                        entry.innerHTML = "Déjà utilisé";
                        entry.className = 'formError';
                    }
                    field.parentNode.appendChild(entry);
                }
        };
        rq.send(params);
    }
    else
        console.log('incomplete');
}
