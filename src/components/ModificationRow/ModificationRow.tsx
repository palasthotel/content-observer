import {useStyles} from './ModificationRow.styles';
import {ModificationResponse} from "../../@types/Modification";

type Props = {
    modification: ModificationResponse
    onClick: () => void
}

const ModificationRow = (
    {
        modification,
        onClick,
    }: Props
) => {
    const styles = useStyles();
    return (
        <div className={styles.component}>
            {modification.site_id} | {modification.content_id} | {modification.content_type} | {modification.type}
            <button onClick={onClick}>Apply</button>
        </div>
    )
}

export default ModificationRow