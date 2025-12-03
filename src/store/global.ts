declare global {
    interface Window {
        ContentObserver: {
            ajax_url: string
            apiKeyParam: string
            apiKeyValue: string
        },
        ContentObserverSettings: {
            ajax_url: string,
            apiKeyValue: string,
            apiNamespace: string,
            pingUrlApiKeyParam: string,
            pingUrl: string,
        }
    }
}

export const getAjaxUrl = () => window.ContentObserver.ajax_url || window.ContentObserverSettings.ajax_url;
export const getApiKeyParam = () => window.ContentObserver.apiKeyParam;
export const getApiKeyValue = () => window.ContentObserver.apiKeyValue || window.ContentObserverSettings.apiKeyValue;
export const getApiKey = () => ({
    [getApiKeyParam()]: getApiKeyValue()
});

export const getApiNamespace = () => window.ContentObserverSettings.apiNamespace;
export const getPingUrlApiKeyParam = () => window.ContentObserverSettings.pingUrlApiKeyParam;
export const getPingUrl = () => window.ContentObserverSettings.pingUrl;