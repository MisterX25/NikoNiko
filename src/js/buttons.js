/**
 * Created by Xavier on 04.09.16.
 */
function button_conf()
{
    var buttons = document.getElementsByTagName('button');
    for (var i = 0; i < buttons.length; i++)
    {
        oneButton = buttons[i];
        if (oneButton.className.indexOf('confirm') >= 0)
        {
            oneButton.onclick = function ()
            {
                return confirm('Sûr ?');
            }
        }
    }

    edButton = document.getElementById('cmdEdit');
    if (edButton != null)
        edButton.onclick = function ()
        {
            set_edit_mode(this.form);
        }

    cancelButton = document.getElementById('cmdCancel');
    if (cancelButton != null)
        cancelButton.onclick = function ()
        {
            history.go(-1);
        }
}