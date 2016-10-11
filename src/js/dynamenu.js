/**
 * Created by Xavier on 10.10.16.
 */

    // Todo:
    //  - Write the body of the createCookie and readCookie functions
    //  - Get the list of persons ('everybody') using Ajax, calling a new script 'getpeople.php' that returns the list in format ["ABC","DEF","IJK"]
    //  - Parse the list to obtain a javascript array ('favorites')
    //  - Build the list of people as we will show it in our menu in two steps:
    //      1. Add people from the favorites list, only if they still exists (=found in the server's response)
    //      2. Add the remaining people from the list, avoiding duplicates
    //  - Add the menu entries below the title. Put them all in a container that appears on hover over the
    //      menu title and disappears when moving out of it
    //  - Add event handlers for clicks. The handler registers the name of the person in the favorites cookie, at the beginning of the list
    //  - Make sure everything works also when the cookie doesn't exist ?

var favorites = JSON.parse(readCookie('favorites'));

function dynamenu_conf()
{
    // Build and send request
    var rq = new XMLHttpRequest();
    rq.open("POST", "src/ajax/getpeople.php", true); // true is for async mode

    rq.onreadystatechange = function ()
    {
        if (rq.readyState == XMLHttpRequest.DONE)
            if (rq.status == 200)
            {
                everybody = JSON.parse(rq.responseText);
                myPeople = mergeLists(favorites, everybody);
                addMenuEntries(myPeople, 'menuPersons');
            }
            else
                alert('Une erreur s\'est produite (' + rq.status + ')');
    };
    rq.send();
}

// Creates a cookie with the given name and value, validity of one year
function createCookie(name, value)
{
    var date = new Date();
    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toUTCString();
    document.cookie = name + "=" + value + expires + "; path=/"; // cookie is valid for the whole site starting from root
}

// Read the value of the cookie with the given name. Returns null if the cookie does not exist
function readCookie(name)
{
    cookies = document.cookie.split(';');
    for (i = 0; i < cookies.length; i++)
    {
        cookie = cookies[i].trim().split('=');
        if (cookie[0] == name) return cookie[1];
    }
    return null;
}

// merges the two lists, placing favorites first
function mergeLists(favorites, everybody)
{
    if (favorites == null) return everybody;

    myPeople = [];
    // first add people from the favorites
    for (i = 0; i < favorites.length; i++)
    {
        stillthere = false; // assume he's not
        for (j = 0; j < everybody.length; j++)
        {
            if (favorites[i] == everybody[j])
            {
                stillthere = true;
                break;
            }
        }
        if (stillthere) myPeople.push(favorites[i]);
    }
    // now add the other people on the list
    for (i = 0; i < everybody.length; i++)
    {
        alreadythere = false; // assume he's not one of the favorites
        for (j = 0; j < favorites.length; j++)
        {
            if (favorites[j] == everybody[i])
            {
                alreadythere = true;
                break;
            }
        }
        if (!alreadythere) myPeople.push(everybody[i]);
    }
    return myPeople;
}

// add the entries to our menu
function addMenuEntries(myPeople, id)
{
    entries = document.createElement('div'); // container for all menu entries
    entries.id = 'menuEntries';
    entries.style.display = 'none'; // hide it at the beginning
    menuTop = document.getElementById(id);
    for (i = 0; i < myPeople.length; i++)
    {
        p = myPeople[i];
        entry = document.createElement('div');
        entry.innerHTML = p;
        entry.className = 'dynamenuentry';
        entry.onclick = function (e)
        {
            showProfile(e.target.innerHTML);
        }; // we can't make a link because we must add it to the favorites
        entries.appendChild(entry);
    }
    menuTop.appendChild(entries);
    menuTop.onclick = function ()
    {
        me = document.getElementById('menuEntries');
        if (me.style.display == 'block')
            me.style.display = 'none';
        else
            me.style.display = 'block';
    };
}

function showProfile(p) // go to the profile of the person, but first records it in the favorites cookie
{
    // check if it is already a favorite
    isFavorite = false;
    if (favorites != null)
    {
        for (i = 0; i < favorites.length; i++)
            if (favorites[i] == p)
            {
                isFavorite = true;
                break;
            }
    }
    else
        favorites = [];

    if (!isFavorite) // then add it
    {
        favorites.unshift(p); // add it at the beginning
        createCookie('favorites', JSON.stringify(favorites));
    }
    window.location = '/NikoNiko/person/'+p;
}