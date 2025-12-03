import {createRoot} from '@wordpress/element';
import ModificationsList from "../components/ModificationsList/ModificationsList";

window.document.addEventListener("DOMContentLoaded", () => {
    const rootElement = document.getElementById("content-observer-modifications");
    if (!rootElement) return;
    const root = createRoot(rootElement);
    root.render(<ModificationsList />);
});