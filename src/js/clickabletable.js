/**
 * Created by Xavier on 12.09.16.
 */

function conf_table()
{
    console.log('table configuration');
    cells=document.getElementsByClassName('clickable');
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
    console.log('cycleVote: '+course+" "+week+" "+attendee);
}
