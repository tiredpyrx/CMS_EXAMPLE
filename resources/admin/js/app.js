import "./bootstrap";
import * as DH from "./helpers/document-helpers";
import * as SH from "./helpers/system-helpers";

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

DH.fadeToggle({
    trigger_id: "document_dropdown_trigger",
    target_id: "document_dropdown",
});

let selectedToggleBoxes = [];
let selectedToggleBoxParents = [];
Array.from(document.querySelectorAll("input[type='checkbox']"))
    .filter((i) => i.id.endsWith("-selectbox"))
    .forEach((i) => {
        i.addEventListener("click", function () {
            let id = i.id.slice(0, i.id.indexOf("-"));
            let parentNodeName = i.dataset.parent_node_name;
            let parent = i;
            if (i.checked && !selectedToggleBoxes.includes(id)) {
                selectedToggleBoxes.push(id);
                while (parent.nodeName != parentNodeName) {
                    parent = parent.parentElement;
                }
                selectedToggleBoxParents.push(parent);
            } else if (!i.checked) {
                let idPos = selectedToggleBoxes.indexOf(id);
                let parentPos = selectedToggleBoxParents.indexOf(parent);

                selectedToggleBoxes.splice(idPos);
                selectedToggleBoxParents.splice(parentPos);
            }
        });
    });

const selectActionDecorator = (callback, { successMessage, failedMessage }) => {
    return function (prefix) {
        callback.call(this, prefix);
        selectedToggleBoxes = [];
        selectedToggleBoxParents.forEach((parent) => {
            parent?.remove();
        });
        toastrAlert("success", successMessage);
    };
};

let selectActionDeleteAllSelected = async (prefix) => {
    await axios.delete(route(prefix + ".deleteAllSelected"), {
        data: {
            ids: selectedToggleBoxes,
        },
    });
};

selectActionDeleteAllSelected = selectActionDecorator(
    selectActionDeleteAllSelected,
    {
        successMessage: "Seçilen kategoriler başarıyla silindi!",
        failedMessage: "Seçilen kategorileri silerken bir sorun oluştu!",
    }
);

window.selectActionDeleteAllSelected = selectActionDeleteAllSelected;
