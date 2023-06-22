/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

// Chargement du Js de Bootstrap
import 'bootstrap';

const addPicFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer cette image';
    removeFormButton.classList.add("btn","btn-warning")
    item.after(removeFormButton);
    console.log(removeFormButton);
    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
}

document
    .querySelectorAll('.picture')
    .forEach((picInput) => {
        addPicFormDeleteLink(picInput)
        console.log(picInput)
    })

// ... the rest of the block from above

const addFormToCollection = (e) => {
    // ...

    // add a delete link to the new form
    addPicFormDeleteLink(item);
    
}