/**
 * Created by Xavier on 12.09.16.
 */

function conf_table()
{
    cells = document.getElementsByClassName('votable');
    for (i = 0; i < cells.length; i++)
    {
        cell = cells[i];
        cells[i].onclick = function ()
        {
            cycleVote(this);
        }
    }
}

function cycleVote(el)
{
    // Retrieve vote info
    course = el.getAttribute('data-course');
    week = el.getAttribute('data-week');
    attendee = el.getAttribute('data-attendee');

    // Build and send request
    params = 'a=' + attendee + '&c=' + course + "&w=" + week;
    var rq = new XMLHttpRequest();
    rq.open("POST", "src/ajax/vote.php", true); // true is for async mode
    rq.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // indicate recipient that parameters are in the url

    rq.onreadystatechange = function ()
    {
        if (rq.readyState == XMLHttpRequest.DONE)
            if (rq.status == 200)
                alert('Une erreur s\'est produite (' + rq.status + ')');
            else
                el.className = 'vote' + rq.responseText + ' votable';
    };
    rq.send(params);
}
