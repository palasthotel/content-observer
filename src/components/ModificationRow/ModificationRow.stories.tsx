import {ComponentMeta} from "@storybook/react";
import ModificationRow from "./ModificationRow";

export default {
    title: 'ModificationRow',
    component: ModificationRow,
    parameters: {
        layout: 'centered',
    },
} as ComponentMeta<typeof ModificationRow>;


export const _ModificationRow = () => <ModificationRow/>