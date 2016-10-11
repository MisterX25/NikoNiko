/**
 * Created by Xavier on 29.08.16.
 */
window.onload = function ()
{
    bodies = document.getElementsByTagName('body');
    page = bodies[0].getAttribute('data-page');

    switch (page)
    {
        case 'links':
            behav_link();
            break;
        case 'person':
            button_conf();
            form_conf();
            break;
        case 'login':
            form_conf();
            resetLink_conf();
            break;
        case 'calendar':
            conf_table();
            break;
    }

    dynamenu_conf();


}
