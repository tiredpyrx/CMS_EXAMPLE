"use strict";

import "./bootstrap";
import * as DH from "./helpers/document-helpers";
import * as SH from "./helpers/system-helpers";
import Sortable from "sortablejs";
window.Sortable = Sortable;

const APP_URL = location.host;
window.APP_URL = APP_URL;

SH.toggleResourcesActive();
DH.changeSluggableFieldsFormat();

DH.slideToggle("sidebar-advanced-trigger", "sidebar-advanced-target");

const APP_BODY = document.body;
const APP_CONTENT = document.getElementById("app_content");
const APP_SIDEBAR = document.getElementById("app_sidebar");
const APP_CATEGORY_EDIT_ICON_MODAL = document.getElementById("app_icon_modal");

// credits https://stackoverflow.com/a/64203190/21720378
const MAX_TOASTS = 2;
toastr.subscribe(function (args) {
    if (args.state === "visible") {
        let toasts = $("#toast-container > *:not([hidden])");
        if (toasts && toasts.length > MAX_TOASTS) toasts[0].hidden = true;
    }
});

if (document.getElementById("user_index_dropdown_trigger"))
    DH.fadeToggle({
        trigger_id: "user_index_dropdown_trigger",
        target_id: "user_index_dropdown_target",
    });

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
                    $(element).fadeToggle();
                }
            }
        });
    });

function toastrAlert(type, message) {
    toastr[type](message);
}

window.toastrAlert = toastrAlert;

DH.fadeToggle({
    trigger_class: "document_dropdown_trigger",
    target_class: "document_dropdown",
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

const selectActionDecorator = (callback) => {
    return function (prefix) {
        if (!selectedToggleBoxes.length) return;
        callback.call(this, prefix);
        selectedToggleBoxes = [];
        selectedToggleBoxParents.forEach((parent) => {
            parent?.remove();
        });
    };
};

let selectActionDeleteAllSelected = async (prefix) => {
    await axios
        .delete(route(prefix + ".deleteAllSelected"), {
            data: {
                ids: selectedToggleBoxes,
            },
        })
        .then((_) =>
            toastrAlert("success", "Seçilen kaynaklar başarıyla silindi!")
        )
        .catch((_) =>
            toastrAlert(
                "error",
                "Seçilen kaynakları silerken bir sorun oluştu!"
            )
        );
};

selectActionDeleteAllSelected = selectActionDecorator(
    selectActionDeleteAllSelected
);

window.selectActionDeleteAllSelected = selectActionDeleteAllSelected;

async function deleteAllUnactivesGlobal(prefix, children, parent_id) {
    let threshold = Array.from(
        document.querySelectorAll('table input[id$="active-togglebox"]')
    ).some((i) => !i.checked);
    if (!threshold) return;
    return await axios
        .delete(
            route(
                prefix +
                    "." +
                    (children
                        ? "deleteAllUnactiveChildren"
                        : "deleteAllUnactives"),
                parent_id
            ),
            {
                data: {
                    modelName: children,
                },
            }
        )
        .then((_) => {
            toastr.success("Aktif olmayan kaynaklar başarıyla silindi!");
            document.querySelectorAll("table tr").forEach((tr) => {
                if (tr.classList.contains("disabled")) tr.remove();
            });
        })
        .catch((_) =>
            toastr.error("Aktif olmayan kaynakları silerken bir sorun oluştu!")
        );
}

window.deleteAllUnactivesGlobal = deleteAllUnactivesGlobal;

function tableResourceAction(el) {
    let rPrefix = el.dataset.route_prefix;
    let rSuffix = el.dataset.route_suffix;
    let successMessage = el.dataset.success_message;
    let errorMessage = el.dataset.error_message;
    let method = el.dataset.method;
    let id = el.dataset.resource_unique;
    let parentNodeName = el.dataset.parent_node_name || false;
    let parent = el;
    if (parentNodeName)
        while (parent.nodeName !== parentNodeName)
            parent = parent.parentElement;
    axios[method](route(rPrefix + "." + rSuffix, id))
        .then((res) => {
            if (parentNodeName) parent?.remove();
            if (!res.data) {
                toastrAlert("error", errorMessage);
                console.error(er.message);
                return 0;
            }
            toastrAlert("success", successMessage);
            return 1;
        })
        .catch((er) => {
            toastrAlert("error", errorMessage);
            console.error(er.message);
        });
}

window.tableResourceAction = tableResourceAction;

APP_SIDEBAR.querySelectorAll("[edit-icon-trigger]").forEach((trigger) => {
    trigger.addEventListener("click", () => {
        let old_icon = trigger.dataset.icon;
        let title = trigger.dataset.title;
        let category = trigger.dataset.unique;

        $(APP_CATEGORY_EDIT_ICON_MODAL).css("display", "grid").hide().fadeIn();
        APP_BODY.style.overflow = "hidden";
        APP_CONTENT.style.filter = "blur(10px)";
        APP_SIDEBAR.style.filter = "blur(10px)";
        APP_CATEGORY_EDIT_ICON_MODAL.querySelector("header h2").innerText =
            title;
        APP_CATEGORY_EDIT_ICON_MODAL.querySelector("input").innerText =
            old_icon;
        const INPUT = APP_CATEGORY_EDIT_ICON_MODAL.querySelector("input");
        INPUT.value = old_icon;
        const SUBMIT =
            APP_CATEGORY_EDIT_ICON_MODAL.querySelector("form button");
        const CLOSE_BUTTON =
            APP_CATEGORY_EDIT_ICON_MODAL.querySelector("[close]");

        if (INPUT.value == old_icon) {
            SUBMIT.disabled = true;
            SUBMIT.classList.add("disabled");
        }

        INPUT.addEventListener("keyup", function () {
            if (this.value == old_icon) {
                SUBMIT.disabled = true;
                SUBMIT.classList.add("disabled");
            } else {
                SUBMIT.disabled = false;
                SUBMIT.classList.remove("disabled");
            }
        });

        const close = () => {
            $(APP_CATEGORY_EDIT_ICON_MODAL).fadeOut();
            APP_BODY.style.overflow = "inital";
            APP_CONTENT.style.filter = "";
            APP_SIDEBAR.style.filter = "";
            reloadAfter(300);
        };

        $(APP_CATEGORY_EDIT_ICON_MODAL).on("click", (e) => {
            if (e.target == APP_CATEGORY_EDIT_ICON_MODAL) close();
        });

        $(CLOSE_BUTTON).on("click", close);

        SUBMIT.addEventListener("click", () => {
            axios
                .patch(route("categories.update.icon", category), {
                    data: {
                        icon: INPUT.value,
                    },
                })
                .then((res) => {
                    toastrAlert(
                        "success",
                        "Kategorinin ikonu başarıyla güncellendi!"
                    );
                    close();
                })
                .catch((_) =>
                    toastrAlert(
                        "error",
                        "Kategorinin ikonunu güncellerken bir şeyler ters gitti!"
                    )
                );
        });
    });
});

/**
 * Reload after given time on miliseconds
 * @param {int} msTime
 */
function reloadAfter(msTime) {
    setTimeout(() => {
        location.reload();
    }, msTime);
}

/**
 * Send DELETE request to FileController destroy method with given file id
 * @param {string} file_id
 */
async function deleteFile(file_id) {
    await axios
        .delete(route("files.destroy", file_id))
        .then((res) => {
            // ? cant validate res, data returns empty string
            return toastrAlert("success", "Medya başarıyla silindi!");
        })
        .catch((_) => {
            return toastrAlert(
                "error",
                "Medyayı silmeyi denerken bir sorun oluştu!"
            );
        });
}

window.deleteFile = deleteFile;
