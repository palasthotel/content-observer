import {useEffect, useState} from '@wordpress/element';
import {useSites} from "../hooks/use-api";
import {filterObservables, filterObservers} from "../data/filter";
import SitesTable from "./SitesTable";
import SiteEditor from "./SiteEditor";
import {Site} from "../../@types/Settings";

const Settings = () => {

    const {
        sites,
        isFetching,
        isSaving,
        update,
        error
    } = useSites();

    const [deletes, setDeletes] = useState<(number | null)[]>([]);
    const [added, setAdded] = useState<Site[]>([]);

    const onDeleteSite = (site: Site) => {
        setDeletes(prev => [...prev, site.id ?? null]);
    }
    const onUndeleteSite = (site: Site) => {
        setDeletes(prev => prev.filter(id => id !== site.id));
    }
    const onAddSite = (site: Site) => {
        setAdded(prev => [...prev, site]);
    }

    const dirtySites = [...sites, ...added];

    const onSaveState = () => {
        update({dirtySites, deletes});
    }

    useEffect(() => {
        if (!isSaving && error.length === 0) {
            setDeletes([]);
            setAdded([]);
        }
    }, [isSaving, error])

    return <>
        <h2>Observers</h2>
        <p>These sites are watching for changes on this site.</p>
        <SitesTable
            sites={filterObservers(dirtySites)}
            isLoading={isFetching}
            deletes={deletes}
            onDelete={onDeleteSite}
            onUndelete={onUndeleteSite}
        />

        <h2>Observables</h2>
        <p>This site is watching for changes on these sites.</p>
        <SitesTable
            sites={filterObservables(dirtySites)}
            isLoading={isFetching}
            deletes={deletes}
            onDelete={onDeleteSite}
            onUndelete={onUndeleteSite}
        />
        <hr/>
        {isSaving ?
            <p>Saving sites. <span className="spinner is-active" style={{float: "left"}}/></p>
            :
            <SiteEditor
                label="Add site"
                onSubmit={onAddSite}
            />
        }

        <hr/>
        {error.length ?
            <p>
                Corrupt state: {error}<br/>
                <a
                    style={{cursor: "pointer"}}
                    onClick={() => window.location.reload()}
                >reload page</a>
            </p>
            :
            <button className="button button-primary" onClick={onSaveState}>Save</button>
        }
    </>;
}

export default Settings;