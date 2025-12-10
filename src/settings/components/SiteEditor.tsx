import {useState} from "@wordpress/element";
import {isValidHttpUrl} from "../data/url";
import {useSiteTest} from "../hooks/use-api";
import {Site} from "../../@types/Settings";

type SiteEditorProps = {
    label: string
    onSubmit: (site: Site) => void
    site?: Site
}

const SiteEditor = (
    {
        label,
        onSubmit,
        site = {
            url: "",
            slug: "",
        }
    }: SiteEditorProps
) => {

    const [url, setUrl] = useState(site?.url ?? "");
    const [slug, setSlug] = useState(site?.slug ?? "");
    const [apiKey, setApiKey] = useState(site?.api_key ?? "");
    const [relationType, setRelationType] = useState(site?.relation_type ?? "observer");

    const isValidUrl = isValidHttpUrl(url);

    const idValidSite = isValidUrl && apiKey.length > 0;

    const elementStyles = {paddingTop: 10};

    const {isTesting, isSuccess, testAgain} = useSiteTest(url, apiKey);

    const slugSuggestion = url
        .toLowerCase()
        .replace(/[^a-z_]/g, "");

    // @ts-ignore
    // @ts-ignore
    // @ts-ignore
    return <div>
        <div style={elementStyles}>
            <label>
                URL<br/>
                <input type="text" value={url} onChange={e => setUrl(e.target.value)} className="regular-text"
                       placeholder="https://example.de/"/>
            </label>
        </div>
        <div style={elementStyles}>
            <label>
                Unique slug<br/>
                <input type="text" value={slug} onChange={e => setSlug(e.target.value)} className="regular-text"
                       placeholder={slugSuggestion}/><br/>
                <p className="description">Must begin with letter. Only lowercase letters and underscores allowed.</p>
            </label>
        </div>
        <div style={elementStyles}>
            <label>
                API Key<br/>
                <input type="text" value={apiKey} onChange={e => setApiKey(e.target.value)} className="regular-text"
                       placeholder="API key of foreign site"/>
            </label>
        </div>
        <div style={elementStyles}>
            <label>
                Relation type<br/>
                <select onChange={e => setRelationType(e.target.value as "observer" | "observable" | "both")} value={relationType}>
                    <option value="observer">Observer</option>
                    <option value="observable">Observable</option>
                    <option value="both">Observer and observable</option>
                </select>
            </label>
        </div>
        <a className="button button-secondary" onClick={() => testAgain()}>
            Test connection {isTesting ?
            <span className="spinner is-active"
                  style={{float: "none", margin: 0, width: 15, height: 15, backgroundSize: "contain"}}/> :
            <span>{isSuccess ? "✅" : "🚨"}</span>
        }
        </a>
        {!isSuccess && !isTesting &&
            <i>Is this plugin installed and activated on the foreign site? Is the api key correct?</i>}
        <div style={elementStyles}>
            <a
                className={`button button-secondary ${idValidSite ? "" : "button-disabled"}`}
                onClick={() => {
                    if (!idValidSite) return;
                    onSubmit({
                        url,
                        slug,
                        api_key: apiKey,
                        relation_type: relationType,
                        registration_time: site?.registration_time ?? Date.now() / 1000,
                    })
                }}
            >{label}</a>
        </div>
    </div>
}

export default SiteEditor;