document.querySelectorAll('a.js-like-link').forEach(function(link){
    link.addEventListener('click', function(event){
        event.preventDefault();

        // La valeur de this dans une fonction qui est liée à un évènement, c'est l'élément html qui déclenche l'évènement lui donc this est le <a> sur lequel on clique
        const url = this.href;
        const spanCount = this.querySelector('span.js-likes')
        const icone = this.querySelector('i');
        
        var httpRequest = new XMLHttpRequest();
        
        httpRequest.onreadystatechange = function (){
            if(httpRequest.readyState === XMLHttpRequest.DONE){
                if(httpRequest.status === 200){
                    var response = JSON.parse(httpRequest.responseText);
                    spanCount.textContent = response.likes;
                    if(icone.classList.contains('fas')){
                        icone.classList.replace('fas', 'far');
                    }else{
                        icone.classList.replace('far', 'fas');
                    }
                }else if(httpRequest.status === 403){
                    window.alert('Vous ne pouvez pas liker un article si vous n\'êtes pas connecté !' );
                }else{
                    window.alert('Une erreur s\'est produite, réessayez plus tard !');
                }
            }
        };   
        httpRequest.open('GET', url, true);
        httpRequest.send();
    })
})

