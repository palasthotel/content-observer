declare global {
    interface Window {
        ContentObserver: {
            ajax_url: string
            apiNamespace: string,
        },
    }
}

export const getAjaxUrl = () => window.ContentObserver.ajax_url;
export const getApiNamespace = () => window.ContentObserver.apiNamespace;
