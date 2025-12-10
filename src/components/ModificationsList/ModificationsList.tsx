import {useModifications} from "../../hooks/use-modifications";
import {useSelectSite, useSites} from "../../hooks/use-sites";
import {useStyles} from './ModificationsList.styles';
import { format } from 'date-fns'
import { de } from 'date-fns/locale'
import {useEffect, useState} from "react";

const dateFormat = (timestamp: number) => {
    const registrationDate = new Date(timestamp * 1000);
    return format(registrationDate,'Pp',{locale:de});
}

const ModificationsList = () => {
    const styles = useStyles();
    const [selectedSiteId, setSelectedSiteId] = useSelectSite();
    const sites = useSites();
    const [operationResultMessage, setOperationResultMessage ] = useState("");
    const [rowOperationResult, setRowOperationResult] = useState<{id: string, success: boolean}>({id: "", success:true})
    const {
        modifications,
        canLoadMore,
        loadMore,
        fetch,
        notify,
        applySince,
    } = useModifications(parseInt(selectedSiteId));

    useEffect(() => {
        if(operationResultMessage == "") return;
        const timeout = setTimeout(()=>{
            setOperationResultMessage("");
        }, 2000);
        return () => clearTimeout(timeout);
    }, [operationResultMessage]);

    useEffect(() => {
        if(rowOperationResult.id == "") return;
        const timeout = setTimeout(()=>{
            setRowOperationResult({id: "", success:false});
        }, 2000);
        return () => clearTimeout(timeout);
    }, [rowOperationResult]);

    const handleFetch = () => {
        fetch().then(res => {
            if(res.success){
                if(res.data.number_of_modifications > 0){
                    setOperationResultMessage(`Fetched ${res.data.number_of_modifications} modifications ✅`);
                } else {
                    setOperationResultMessage(`Modifications already up to date ✅`);
                }
            } else {
                setOperationResultMessage("Something went wrong 🚨");
            }
        })
    };
    const handleNotify = ()=> {
        notify().then(res => {
            if(res.success){
                setOperationResultMessage("Operation successful ✅");
            } else {
                setOperationResultMessage("Something went wrong 🚨");
            }
        })
    };

    const handleApply = (rowId: string, since: number)=> {
        applySince(since).then(res => {
            setRowOperationResult({id: rowId, success: res.success});
        })
    }

    let lastTime = 0;
    return (
        <div className={styles.component}>
            <h1 className="wp-heading-inline">Modifications</h1>
            <div className={`tablenav top ${styles.tablenav}`}>
                <select
                    onChange={(e)=>setSelectedSiteId(e.target.value)}
                    value={selectedSiteId}
                >
                    <option value="0">- current site -</option>
                    {sites.map(site => <option value={site.id}>{site.slug}</option>)}
                </select>
                <button
                    className="button button-secondary"
                    onClick={handleFetch}
                    disabled={selectedSiteId == "0"}
                >
                    Fetch
                </button>
                <button
                    className="button button-secondary"
                    onClick={handleNotify}
                    disabled={selectedSiteId == "0"}
                >
                    Notify
                </button>
                {operationResultMessage ? <p className="description">{operationResultMessage}</p> : null }
            </div>
            <table className="wp-list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <th scope="col" id="type" className="manage-column">Type</th>
                    <th scope="col" id="content-type" className="manage-column">Content Type</th>
                    <th scope="col" id="id" className="manage-column">Content Id</th>
                    <th scope="col" id="actions" className="manage-column">Actions</th>
                </tr>
                </thead>
                <tbody>
                {modifications.mods.map((mod)=> {
                    const needApplyButton = mod.modified != lastTime;
                    lastTime = mod.modified;
                    const key = `${mod.site_id}-${mod.content_type}-${mod.content_id}`
                    const showResult = rowOperationResult.id == key;
                    const icon = rowOperationResult.success ? "✅" : "🚨"
                    return (
                        <tr className={needApplyButton ? styles.firstRow : undefined } key={key}>
                            <td>{mod.type}</td>
                            <td>{mod.content_type}</td>
                            <td>{mod.content_id}</td>
                            <td>
                                {needApplyButton ?
                                    <button
                                        className="button button-secondary button-small"
                                        onClick={()=> handleApply(key, mod.modified)}
                                    >
                                        Apply {dateFormat(mod.modified)} and newer {showResult ? icon : null }
                                    </button>
                                    :
                                    null
                                }
                            </td>
                        </tr>
                    )
                })}
                </tbody>
            </table>
            {
                canLoadMore &&
                <button onClick={loadMore}>
                    Load more
                </button>
            }

        </div>
    )
}

export default ModificationsList
