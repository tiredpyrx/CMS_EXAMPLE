import "./bootstrap";
import * as DH from "./helpers/document-helpers";
import * as SH from "./helpers/system-helpers";
import axios from "axios";

DH.replaceToIcon();
SH.toggleResourcesActive();

DH.slideToggle("sidebar-advanced-trigger", "sidebar-advanced-target");


if (document.getElementById("user_index_dropdown_trigger"))
    DH.fadeToggle({
        trigger_id: "user_index_dropdown_trigger",
        target_id: "user_index_dropdown_target",
    });

// customs
document
    .querySelectorAll(".close-on-outside-click")
    .forEach(function (element) {
        $(document).on("click touchstart", function (e) {
            if (!element.contains(e.target)) {
                if (
                    element.getAttribute("style") &&
                    !element.style.opacity &&
                    element.style.display != "none" &&
                    (element.dataset.method === "hide" ||
                        !element.dataset.method)
                ) {
                    DH.fadeToggle({ target_id: element.id });
                }
            }
        });
    });

function toastrAlert(type, success) {
    toastr[type](success);
}

window.toastrAlert = toastrAlert;

document.querySelectorAll("input[type='checkbox']").forEach((i) => {
    i.value = i.checked;
    i.addEventListener("click", () => (i.value = new Boolean(i.checked)));
});
