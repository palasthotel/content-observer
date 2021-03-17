import React from "react";
import { format } from 'date-fns'
import { de } from 'date-fns/locale'

const SiteRow = ({site, onDelete, hasDeleteFlag, onUndelete})=>{
    const notificationDate = new Date(site.last_notification_time * 1000);
    const registrationDate = new Date(site.registration_time * 1000);

    const titleStyle = hasDeleteFlag? {textDecoration: "line-through"} : {};

    return <tr>
        <td className="title column-title has-row-actions column-primary page-title">
            <strong style={titleStyle}>
            {site.url}
            </strong>
            <i>{site.api_key}</i>
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
        <td>{format(notificationDate,'Pp',{locale:de})}</td>
        <td>{format(registrationDate,'Pp',{locale:de})}</td>
    </tr>
}

const SitesTable = ({sites, isLoading, deletes, onDelete, onUndelete})=>{
    return <table className="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <td>URL / API Key</td>
                <td style={{width: 120}}>Last notification</td>
                <td style={{width: 120}}>Registration</td>
            </tr>
        </thead>
        <tbody style={{minHeight: 20}}>

            {sites.map(s=> <SiteRow
                key={s.id}
                site={s}
                hasDeleteFlag={deletes.includes(s.id)}
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