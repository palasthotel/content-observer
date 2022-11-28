import {useStyles} from './ModificationsList.styles';
import {useModifications} from "../../hooks/use-modifications";
import {ModificationResponse} from "../../@types/Modification";
import ModificationRow from "../ModificationRow/ModificationRow";
import {useSites} from "../../hooks/use-sites";

const ModificationsList = () => {
    const styles = useStyles();
    const sites = useSites();
    const {
        modifications,
        canLoadMore,
        loadMore,
    } = useModifications();

    const handleClick = (mod: ModificationResponse) => () => {
        console.debug(mod);
    }

    return (
        <div className={styles.component}>
            <select>
                {sites.map(site => {
                    return (
                        <option key={site.id} value={site.id}>{site.slug}</option>
                    )
                })}
            </select>
            {modifications.mods.map((mod,index)=> {
                return (
                    <ModificationRow
                        key={index}
                        modification={mod}
                        onClick={handleClick(mod)}
                    />
                )
            })}
            {
                canLoadMore &&
                <button onClick={loadMore}>
                    Load more
                </button>
            }

        </div>
    )
}

export default ModificationsList