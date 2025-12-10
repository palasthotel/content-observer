import {createRoot} from '@wordpress/element';
import Settings from "./components/Settings";

window.document.addEventListener("DOMContentLoaded", () => {
    const rootElement = document.getElementById("content-sync__sites");
    if (!rootElement) return;
    const root = createRoot(rootElement);
    root.render(<Settings/>);
});