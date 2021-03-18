import apiFetch from "@wordpress/api-fetch";

export const fetchSites = () => {
    const {apiNamespace} = ContentSync;
    return apiFetch({path: `/${apiNamespace}/sites`}).then(data => {
        console.debug("fetchSites", data);
        return data;
    });
}

export const postSites = ({dirtySites, deletes}) => {
    const {apiNamespace} = ContentSync;
    return apiFetch({
        path: `/${apiNamespace}/sites`, method: "POST", data: {
            dirty_sites: dirtySites.map(s=>({
                id: s.id ?? null,
                url: s.url,
                api_key: s.api_key,
                relation_type: s.relation_type,
            })),
            deletes
        }
    }).then(response => {
        console.debug("postSites", response);
        return response;
    })
}

export const getTestSite = (site_url, api_key)=>{
    const controller = new AbortController();
    const { pingUrl, pingUrlApiKeyParam} = ContentSync;
    const url = `${site_url}${pingUrl}?${pingUrlApiKeyParam}=${api_key}`;
    const promise = fetch(url, {signal:controller.signal}).then(result=>result.json()).then(json=>{
        return json.response === "pong";
    }).catch(e=>{
        console.error(e);
        return e;
    });
    return {
        promise,
        cancel: ()=>{
            controller.abort();
        }
    }
}