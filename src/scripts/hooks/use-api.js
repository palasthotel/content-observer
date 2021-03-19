import {useEffect, useState} from "@wordpress/element";
import {fetchSites, getTestSite, getTestSiteById, postSites} from "../data/api";
import {isValidHttpUrl} from "../data/url";

export const useSites = () => {

    const [fetchState, setFetchState] = useState(1);
    const [isFetching, setIsFetching] = useState(false);
    const [isSaving, setIsSaving] = useState(false);
    const [state, setState] = useState([]);
    const [error, setError] = useState("");

    useEffect(() => {
        setIsFetching(true);
        fetchSites().then(sites => {
            setIsFetching(false);
            setState(sites);
        });
    }, [fetchState]);

    return {
        sites: state,
        error,
        isFetching,
        isSaving,
        update: ({dirtySites, deletes}) => {
            setIsSaving(true);
            postSites({
                dirtySites,
                deletes,
            }).then(sites => {
                console.log("set sites", sites)
                setIsSaving(false);
                setState(sites);
            }).catch(error => {
                console.error(error);
                setError(error.message);
            });
        },
    };
}

export const useSiteTest = (site_url, site_api_key) => {

    const [isTesting, setIsTesting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(true);
    const [testAgain, setTestAgain] = useState(1);

    useEffect(()=>{
        if(isTesting) return;

        if(!isValidHttpUrl(site_url)){
            setIsSuccess(false);
            return;
        }

        if(site_api_key.length <= 0){
            setIsSuccess(false);
            return;
        }

        setIsTesting(true);
        const {promise, cancel} = getTestSite(site_url, site_api_key);

        promise.then((success)=>{
            console.log(success);
            setIsTesting(false);
            setIsSuccess(success === true);
        });

        return ()=> cancel();

    }, [site_url, site_api_key, testAgain]);

    return {
        isSuccess,
        isTesting,
        testAgain: ()=> setTestAgain(t=>t+1),
    }
}

export const useSiteTestById = (site_id) => {

    const [isTesting, setIsTesting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(true);
    const [testAgain, setTestAgain] = useState(1);

    useEffect(()=>{
        if(isTesting) return;

        setIsTesting(true);
        getTestSiteById(site_id).then((success)=>{
            console.log(success);
            setIsTesting(false);
            setIsSuccess(success === true);
        });

    }, [site_id, testAgain]);

    return {
        isSuccess,
        isTesting,
        testAgain: ()=> setTestAgain(t=>t+1),
    }
}