import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ["search_results" ]
    static values = {
        execution: { type: Boolean, default: false }
      }
    searching(event){
        // console.log('debut de searching et execution = '+ this.executionValue);
        // console.log('on vide la div, qui contenait "'+event.target.value+'"');
        this.search_resultsTarget.innerHTML="";
        if((event.target.value.length > 3) && (this.executionValue === false)){
            this.executionValue = true;
            // console.log('dans la boucle et execution = '+ this.executionValue);
            let url = '/search/' + event.target.value
            fetch(url)
            .then((response) => response.json())
            .then((data)=>{
                // console.log(data)
                data.forEach(element => {
                    // console.log('on crée un <li>')
                    let li = document.createElement("li");
                    // console.log('on crée un <a>')
                    let anchor = document.createElement("a");
                    anchor.textContent = element.name +' : '+ element.description;
                    anchor.href = "/location/"+element.id;
                    li.appendChild(anchor);
                    // console.log('on ajoute un <li> dans la liste')
                    this.search_resultsTarget.appendChild(li);
                    this.executionValue = false;
                    // console.log('le fetch est fini et execution = '+ this.executionValue);
                });
            })
        }
    }

    send_search(event){
        console.log("t'as appuyé sur enter");
        let url = '/search/' + event.target.value
        fetch(url)
        .then((response) => response.json())
        .then((data)=>{
            if(data.length !== 0){
                "/location/"+data[0].id;
                window.location.href = "/location/"+data[0].id;
            }else{
                alert('La recherche n\'a rien donnée')
            } 
        })
    }
}
