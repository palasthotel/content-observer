import React from 'react';
import Observers from "./components/Observers.jsx";
import Observables from "./components/Observables.jsx";
import {useSites} from "./hooks/use-api";

const Settings = ()=>{

    const [sites] = useSites();

    return <>
        <h2>Content observers</h2>
        <p>We provide them with our content.</p>
        <Observers observers={sites.filter(s=>s.type === "observer")} />
        <h2>Content observables</h2>
        <p>They provide us with their content.</p>
        <Observables observables={sites.filter(s=>s.type === "observable")} />
    </>;
}

export default Settings;