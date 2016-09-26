/**
 * Created by Xavier on 12.09.16.
 */
function conf_table()
{
    cells=document.getElementsByClassName('votable');
    for (i=0; i<cells.length; i++)
    {
        cell=cells[i];
        cells[i].onclick = function ()
        {
            cycleVote(this);
        }
    }
}

function cycleVote(el)
{
    course = el.getAttribute('data-course');
    week = el.getAttribute('data-week');
    attendee = el.getAttribute('data-attendee');
    vclass = el.className;
    pos=vclass.indexOf('vote');
    vote = vclass.substring(pos+4,pos+5);
    el.className = el.className.replace('vote'+vote+' ',''); // remove vote class
    switch (vote)
    {
        case '0': vote = '1'; break;
        case '1': vote = '2'; break;
        case '2': vote = '3'; break;
        case '3': vote = '9'; break;
        case '9': vote = '0'; break;
        default: vote = '0'; break;
    }
    el.className = 'vote'+vote+' '+el.className; // set new value in table

    // Store it
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'src/ajax/vote.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status !== 200) {
            alert('Request failed.  Returned status of ' + xhr.status);
        }
    };
    xhr.send(encodeURI('c='+course+'&w='+week+'&a='+attendee+'&v='+vote));
}