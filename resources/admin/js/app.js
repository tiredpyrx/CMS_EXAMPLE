"use strict";

import "./bootstrap";
import * as DH from "./helpers/document-helpers";
import * as SH from "./helpers/system-helpers";

DH.replaceToIcon();
SH.toggleResourcesActive();

DH.slideToggle("sidebar-advanced-trigger", "sidebar-advanced-target");

const APP_BODY = document.body;
const APP_CONTENT = document.getElementById("app_content");
const APP_SIDEBAR = document.getElementById("app_sidebar");
const APP_CATEGORY_EDIT_ICON_MODAL = document.getElementById("app_icon_modal");

// credits https://stackoverflow.com/a/64203190/21720378
const MAX_TOASTS = 2;
toastr.subscribe(function (args) {
    if (args.state === "visible") {
        var toasts = $("#toast-container > *:not([hidden])");
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
            console.info(res);
            if (!res.data) {
                toastrAlert("error", errorMessage);
                return 0;
            }
            toastrAlert("success", successMessage);
            return 1;
        })
        .catch((_) => {
            toastrAlert("error", errorMessage);
            console.warn(_.message);
        });
}

window.tableResourceAction = tableResourceAction;

if (route().current("categories.create")) {
    tippy("[for='title']", {
        content: "Kategori başlık, zorunlu, en fazla 60 karakter",
    });
    tippy("[for='icon']", {
        content: "Kategori ikon, en fazla 60 karakter",
    });
    tippy("[for='view']", {
        content: "Kategori dosya ismi, en fazla 60 karakter",
    });
    tippy("[for='description']", {
        content: "Kategori açıklama, en fazla 160 karakter",
    });
    tippy("[for='have_details']", {
        content:
            "Kategorinin gönderilerine slug alanı aç, her gönderi bir sayfayı temsil eder, gönderinin özel dosya ismi varsa o kullanılır, varsayılan olarak kategorinin dosyası kullanılır",
    });
    tippy("[for='as_page']", {
        content:
            "Kategorinin kendisine slug alanı açar, gönderi sayısını 1'e sabitler! Kategori sayfa olarak kullanılır",
    });
    tippy("[for='active']", {
        content: "Kategorinin aktif durumu",
    });
}

if (route().current("posts.create") || route().current("posts.edit")) {
    document.querySelectorAll("label").forEach((label) => {
        // COPY HANDLERS VIA LABEL CLICK
        label.addEventListener("click", () => {
            let handler = label.dataset.handler;
            if (handler.endsWith("[]"))
                handler = handler.substring(0, handler.lastIndexOf("["));
            navigator.clipboard.writeText(`->field('${handler}')`);
        });

        // DISPLAY FIELD DESCRIPTION VIA LABEL HOVER
        label.addEventListener("mouseover", () => {
            let description = label?.dataset.description;
            if (description) tippy(label, { content: description });
        });
    });

    document
        .querySelectorAll("#document-grid .document-item")
        .forEach((item) => {
            let i = item.querySelector("input");
            let t = item.querySelector("textarea");
            let d = item.querySelector("div");
            let cs = item.querySelector("header .char-show");
            if (i) {
                if (i.type !== "text" && !t) return;
                let iVal = i.value;
                cs.textContent = iVal.length;
                i?.addEventListener("input", (e) => {
                    iVal = e.target.value;
                    item.querySelector("header .char-show").textContent =
                        iVal.length;

                    if (i.dataset.max_value && iVal >= i.dataset.max_value)
                        cs.style.color = "red";
                    else cs.style.color = "inherit";
                });
            } else if (t) {
                let tVal = t.value;
                item.querySelector("header .char-show").textContent =
                    tVal.length;
                t?.addEventListener("input", (e) => {
                    tVal = e.target.value;
                    item.querySelector("header .char-show").textContent =
                        tVal.length;
                });

                if (t.dataset.max_value && tVal >= t.dataset.max_value)
                    cs.style.color = "red";
                else cs.style.color = "inherit";
            }
        });
}

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
                    console.info(res);
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

function reloadAfter(msTime) {
    setTimeout(() => {
        location.reload();
    }, msTime);
}

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
