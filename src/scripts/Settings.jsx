import React, {useState} from 'react';
import {useSites} from "./hooks/use-api";
import {filterObservables, filterObservers} from "./data/filter";
import SitesTable from "./components/SitesTable.jsx";

const AddSite = ({label, onClick}) => {
    return <div style={{
        paddingTop: 10,
    }}>
        <a
            className="button button-secondary"
            onClick={onClick}
        >{label}</a>
    </div>
}

const Settings = () => {

    const [sites, isFetching] = useSites();

    const [deletes, setDeletes] = useState([]);
    const [added, setAdded] = useState([]);

    const onDeleteSite = (site)=>{
        setDeletes([...deletes, site.id]);
    }
    const onUndeleteSite = (site)=>{
        setDeletes(deletes.filter(id=>id !== site.id));
    }
    const onAddSite = (relation_type)=>{
        setAdded()
    }

    return <>
        <h2>Observers</h2>
        <p>These sites are watching for changes on this site.</p>
        <SitesTable
            sites={filterObservers(sites)}
            isLoading={isFetching}
            deletes={deletes}
            onDelete={onDeleteSite}
            onUndelete={onUndeleteSite}
        />
        <AddSite label="Add observer site" onClick={()=>{
            console.debug("Add observer");
        }} />

        <h2>Observables</h2>
        <p>This site is watching for changes on these sites.</p>
        <SitesTable
            sites={filterObservables(sites)}
            isLoading={isFetching}
            deletes={deletes}
            onDelete={onDeleteSite}
            onUndelete={onUndeleteSite}
        />
        <AddSite
            label="Add observable site"
            onClick={()=>{

                console.debug("Add observable");
            }}
        />
        <hr />
        <button className="button button-primary">Save</button>

    </>;
}

export default Settings;