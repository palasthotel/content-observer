import {render} from '@wordpress/element';
import ModificationsList from "../components/ModificationsList/ModificationsList";

window.document.addEventListener("DOMContentLoaded", () => {
   const root = document.getElementById("content-observer-modifications");
   render(<ModificationsList />, root);
});