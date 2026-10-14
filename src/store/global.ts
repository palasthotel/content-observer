declare global {
    interface Window {
        ContentObserver: {
            ajax_url: string
            ajax_nonce: string
            apiNamespace: string,
        },
    }
}

export const getAjaxUrl = () => window.ContentObserver.ajax_url;
export const getAjaxNonce = () => window.ContentObserver.ajax_nonce;
export const getApiNamespace = () => window.ContentObserver.apiNamespace;
