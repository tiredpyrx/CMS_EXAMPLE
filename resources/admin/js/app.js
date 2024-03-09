import "./bootstrap";
import * as DH from "./helpers/document-helpers";

function $_slide_toggle(trigger_id, target_id) {
    $(`#${trigger_id}`).on("click touchstart", () => {
        $(`#${target_id}`).slideToggle();
    });
}

DH.replaceToIcon();

$_slide_toggle("sidebar-advanced-trigger", "sidebar-advanced-target");
