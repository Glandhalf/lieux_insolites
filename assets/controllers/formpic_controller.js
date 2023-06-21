import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["formspic" ]
    static values = {
        counter: { type: Number, default: 1 }
    }
    
    addPicForm(event){
        // console.log(this.counterValue)
        // console.log('coucou');
        // console.log(event);
        // console.log(event.target.dataset.template);
        let newformpic = event.target.dataset.template;
        // console.log(event.target.dataset.index)
        // console.log(newformpic);
        newformpic = newformpic.replace(/__name__/g, this.counterValue);
        // console.log(newformpic);
        this.formspicTarget.innerHTML += newformpic;
        this.counterValue ++;
        event.target.dataset.index = this.counterValue;
    }
}
