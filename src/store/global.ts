declare global {
    interface Window {
        ContentObserver: {
            ajax_url: string
            apiKeyParam: string
            apiKeyValue: string
            apiNamespace: string,
            pingUrlApiKeyParam: string,
            pingUrl: string,
        },
    }
}

export const getAjaxUrl = () => window.ContentObserver.ajax_url;
export const getApiKeyParam = () => window.ContentObserver.apiKeyParam;
export const getApiKeyValue = () => window.ContentObserver.apiKeyValue;
export const getApiKeyPair = () => ({
    [getApiKeyParam()]: getApiKeyValue()
});

export const getApiNamespace = () => window.ContentObserver.apiNamespace;
export const getPingUrlApiKeyParam = () => window.ContentObserver.pingUrlApiKeyParam;
export const getPingUrl = () => window.ContentObserver.pingUrl;