export const isValidHttpUrl = (string: string) => {
    let url;
    try {
        url = new URL(string);
    } catch (_) {
        return false;
    }

    if (url.hostname.length < 3 || url.hostname.indexOf(".") <= 0) {
        return false;
    }

    return url.protocol === "http:" || url.protocol === "https:";
}