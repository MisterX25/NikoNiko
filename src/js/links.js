/**
 * Created by Xavier on 29.08.16.
 */

function behav_link(lien){
    var liens = document.getElementsByTagName('a');
    for (var i = 0 ; i < liens.length ; i++) {
        lien = liens[i];
        //Ouverture des liens dans une fenêtre externe
        if (lien.className == 'ext') {
            lien.title = 'S\'ouvre dans une nouvelle fenêtre';
            lien.onclick = function () {
                window.open(this.href);
                return false;
            };
        }
        //Confirmation
        if (lien.className.indexOf('confirm') >= 0) {
            lien.title = 'Confirmer l\'ouverture';
            lien.onclick = function () {
                if (confirm('Ouvrir ce site ?'))
                    document.location.href = this.href;
                return false;
            };
        }
        //Fermeture des fenêtres
        if (lien.className.indexOf('close') >= 0) {
            lien.title = 'Fermer cette fenêtre';
            lien.onclick = function () {
                window.close();
                return false;
            };
        }
        //Print
        if (lien.id == 'print_link' || lien.getAttribute('data-behavior') == 'print') {
            lien.onclick = function () {
                window.print();
                return false;
            };
        }
        //Ouverture d'une image en PopUp
        if (lien.className.indexOf('popup1') >= 0) {
            lien.onclick = function () {
                window.open(this.href, '', '');
                return false;
            };
        }
        //Ouverture d'une image en PopUp 2
        if (lien.className.indexOf('popup2') >= 0) {
            lien.onclick = function () {
                var ImagesLoader = new Image();
                ImagesLoader.onload = function () {
                    window.open(ImagesLoader.src, '',
                        'width=' + ImagesLoader.width + ', ' +
                        'height=' + ImagesLoader.height + ', ' +
                        'left=' + (screen.width - ImagesLoader.width) / 2 + ', ' +
                        'top=0'
                    );
                };
                ImagesLoader.src = this.href;
                return false;
            };
        }
        // mail
        if(lien.id == 'mail')
        {
            lien.onmouseover = function(){
                document.getElementById('mailimg').src = 'assets/images/icons/maila.gif';
            };
            lien.onmouseout = function(){
                document.getElementById('mailimg').src = 'assets/images/icons/mail.gif';
            };
        }
    }
}