import { format } from 'date-fns'
import { de } from 'date-fns/locale'
import {useSiteTest} from "../hooks/use-api";
import {Site} from "../../@types/Settings";

type SiteRowProps = {
    site: Site,
    onDelete: () => void,
    hasDeleteFlag: boolean,
    onUndelete: () => void,
}
const SiteRow = (
    {
        site,
        onDelete,
        hasDeleteFlag,
        onUndelete
    }: SiteRowProps
)=>{

    const {isSuccess, isTesting, testAgain} = useSiteTest(site.url, site.api_key);

    let notificationDateString = "---";
    if(site.last_notification_time){
        const notificationDate = new Date(site.last_notification_time * 1000);
        notificationDateString = format(notificationDate,'Pp',{locale:de})
    }

    let registrationDateString = "---";
    if(site.registration_time){
        const registrationDate = new Date(site.registration_time * 1000);
        registrationDateString = format(registrationDate,'Pp',{locale:de});
    }

    const titleStyle = hasDeleteFlag? {textDecoration: "line-through"} : {};

    return <tr>
        <td className="title column-title has-row-actions column-primary page-title">
            <strong style={titleStyle}>
            {site.url}
            </strong>
            API Key: <code>{site.api_key}</code><br/>
            Slug: <code>{site.slug}</code>
            <div className="row-actions">
                {hasDeleteFlag ?
                    <span className="edit">
                        <a
                            style={{cursor:"pointer"}}
                            onClick={onUndelete}
                        >Do not delete</a>
                    </span>
                :
                    <span className="trash">
                        <a
                            style={{cursor:"pointer"}}
                            className="submitdelete"
                            onClick={onDelete}
                        >Delete</a>
                    </span>
                }

            </div>
        </td>
        <td>{notificationDateString}</td>
        <td>{registrationDateString}</td>
        <td>
            <div>
                <a className="button button-secondary" onClick={()=>testAgain()}>
                    Test connection {isTesting ?
                        <span className="spinner is-active" style={{float:"none", margin: 0, width: 15, height: 15, backgroundSize:"contain"}} />:
                        <span>{isSuccess ? "✅": "🚨"}</span>
                    }
                </a>
            </div>
            {!isSuccess && !isTesting && <i>Is this plugin installed and activated on the foreign site? Is the api key correct?</i>}
        </td>
    </tr>
}

type SitesTableProps = {
    sites: Site[],
    isLoading: boolean,
    deletes: (number|null)[],
    onDelete: (site: Site) => void,
    onUndelete: (site: Site) => void,
}
const SitesTable = (
    {
        sites,
        isLoading,
        deletes,
        onDelete,
        onUndelete
    }: SitesTableProps
)=>{
    return <table className="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <td>URL / API Key</td>
                <td style={{width: 120}}>Last notification</td>
                <td style={{width: 120}}>Registration</td>
                <td style={{width: 140}}></td>
            </tr>
        </thead>
        <tbody style={{minHeight: 20}}>

            {sites.map(s=> <SiteRow
                key={s.url}
                site={s}
                hasDeleteFlag={(!s.id ? false : deletes.includes(s.id))}
                onDelete={()=> onDelete(s)}
                onUndelete={()=> onUndelete(s)}
            />)}

            {isLoading && <tr>
                <td colSpan={3}>
                    <span className="spinner is-active" style={{float:"left"}} />
                </td>
            </tr>}

        </tbody>
    </table>
};

export default SitesTable;