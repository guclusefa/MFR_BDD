function clickEtab(){
    etab = document.getElementById('newetab').value
    link = "fiche_etablissement?id="+etab
    window.open(link, '_blank');
}

function clickEtab2(){
    etab = document.getElementById('newcfa').value
    link = "fiche_etablissement?id="+etab
    window.open(link, '_blank');
}

function clickContact(){
    contact = document.getElementById('newcontact').value
    link = "fiche_contact?id="+contact
    if(contact > 0 ){
        window.open(link, '_blank');
    }
}

function clickMembre(){
    membre = document.getElementById('newmembre').value
    link = "fiche_membre?id="+membre
    if(membre > 0 ){
        window.open(link, '_blank');
    }
}