import {render} from '@wordpress/element';
import Settings from "./components/Settings.jsx";

jQuery(()=>{
    const domSites = document.getElementById("content-sync__sites");
    render(<Settings />, domSites);
});