import apiFetch from "@wordpress/api-fetch";

const config = ContentObserver;

const getCustomHeaders = ()=>{
    const {customRequestHeaders} = config;
    let headers = new Headers();
    for(const key in customRequestHeaders){
        if(!customRequestHeaders.hasOwnProperty(key)) continue;
        headers.set(key, customRequestHeaders[key]);
    }
    return headers;
}

export const fetchSites = () => {
    const {apiNamespace} = config;
    return apiFetch(
        {
            path: `/${apiNamespace}/sites`,
            method: "GET",
            headers: getCustomHeaders(),
        }).then(data => {
        console.debug("fetchSites", data);
        return data;
    });
}

export const postSites = ({dirtySites, deletes}) => {
    const {apiNamespace} = config;
    return apiFetch({
        path: `/${apiNamespace}/sites`,
        method: "POST",
        headers: getCustomHeaders(),
        data: {
            dirty_sites: dirtySites.map(s=>({
                id: s.id ?? null,
                slug: s.slug,
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
    const { pingUrl, pingUrlApiKeyParam} = config;
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

export const getTestSiteById = (site_id)=>{
    const {apiNamespace,pingUrlApiKeyParam, apiKey} = config;
    const url = `${apiNamespace}/ping?${pingUrlApiKeyParam}=${apiKey}&site_id=${site_id}`;
    return apiFetch({path: url, signal: controller.signal}).then(data=>{
        console.debug(data);
        return data.response === "pong";
    })
}