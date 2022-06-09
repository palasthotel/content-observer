declare global {
    interface Window {
        ContentObserver: {
            apiKeyParam: string
            apiKeyValue: string
        }
    }
}

export const getApiKeyParam = () => window.ContentObserver.apiKeyParam;
export const getApiKeyValue = () => window.ContentObserver.apiKeyValue;
export const getApiKey = () => ({
    [getApiKeyParam()]:getApiKeyValue()
});