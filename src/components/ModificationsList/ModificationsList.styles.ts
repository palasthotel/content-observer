import {createUseStyles} from "react-jss";

export const useStyles = createUseStyles({
    component: {

    },
    tablenav: {
        display: "flex",
        gap: 6,
    },
    firstRow: {
        "& td": {
            borderTop: "2px solid black",
        }
    }
});
