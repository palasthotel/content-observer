import apiFetch from "@wordpress/api-fetch";
import {getApiNamespace, getPingUrlApiKeyParam, getApiKeyPair, getApiKeyValue} from "../../store/global";
import {Site} from "../../@types/Settings";

type SitesResponse = Site[]
export const fetchSites = (
): Promise<SitesResponse> => {
    const apiNamespace = getApiNamespace();
    return apiFetch(
        {
            path: `/${apiNamespace}/sites`,
            method: "GET",
        }).then(data => {
        return data;
    }) as Promise<SitesResponse>;
}

type PostSitesArgs = {
    dirtySites: Site[];
    deletes: (number | null)[];
}
export const postSites = (
    {
        dirtySites,
        deletes
    }: PostSitesArgs
): Promise<SitesResponse> => {
    const apiNamespace = getApiNamespace();
    return apiFetch({
        path: `/${apiNamespace}/sites`,
        method: "POST",
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
    }) as Promise<SitesResponse>
}

type TestSiteArgs = {
    site_url: string;
    site_api_key: string;
}
type TestSiteResult = {
    promise: Promise<boolean>;
    cancel: () => void;
};

type PingResponse = {
    response: string;
}

export const getTestSite = (
    {
        site_url,
        site_api_key,
    }: TestSiteArgs
): TestSiteResult => {
    const apiNamespace = getApiNamespace();
    const pingUrlApiKeyParam = getPingUrlApiKeyParam();
    const apiKeyValue = getApiKeyValue();
    const url = `${apiNamespace}/ping?${pingUrlApiKeyParam}=${apiKeyValue}&site_url=${encodeURIComponent(site_url)}&site_api_key=${site_api_key}`;
    const controller = new AbortController();
    const promise = (apiFetch({
        path: url,
        signal: controller.signal,
    }) as Promise<PingResponse>).then(json => {
        return json.response === "pong";
    }).catch(e => {
        console.error(e);
        return false;
    }) as Promise<boolean>;

    return {
        promise,
        cancel: ()=>{
            controller.abort();
        }
    }
}

export const getTestSiteById = (site_id: string)=>{
    const apiNamespace = getApiNamespace();
    const pingUrlApiKeyParam = getPingUrlApiKeyParam();
    const apiKeyValue = getApiKeyValue();
    const url = `${apiNamespace}/ping?${pingUrlApiKeyParam}=${apiKeyValue}&site_id=${site_id}`;
    const controller = new AbortController();

    return (apiFetch({
        path: url,
        signal: controller.signal,
    }) as Promise<PingResponse>).then(data=>{
        return data.response === "pong";
    }) as Promise<boolean>;
}