import apiFetch from "@wordpress/api-fetch";

const config = ContentObserver;

const getCustomHeaders = ()=>{
    const {customRequestHeaders} = config;
    return customRequestHeaders;
}

export const fetchSites = () => {
    const {apiNamespace} = config;
    return apiFetch(
        {
            path: `/${apiNamespace}/sites`,
            method: "GET",
            headers: getCustomHeaders(),
        }).then(data => {
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
        return response;
    })
}

export const getTestSite = (site_url, api_key)=>{
    const {apiNamespace,pingUrlApiKeyParam, apiKey} = config;
    const url = `${apiNamespace}/ping?${pingUrlApiKeyParam}=${apiKey}&site_url=${encodeURIComponent(site_url)}&site_api_key=${api_key}`;
    const controller = new AbortController();
    const promise = apiFetch(
        {
            path: url,
            signal:controller.signal,
            headers: getCustomHeaders(),
        }
    ).then(json=>{
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
    const controller = new AbortController();

    return apiFetch({
        path: url,
        signal: controller.signal,
        headers: getCustomHeaders(),
    }).then(data=>{
        return data.response === "pong";
    })
}