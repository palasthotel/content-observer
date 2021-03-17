import React from 'react';
import {render} from 'react-dom';
import Settings from "./Settings.jsx";

jQuery(($)=>{

    const domSites = document.getElementById("content-sync__sites");
    render(<Settings />, domSites);

});
// populate observables

// populate observers