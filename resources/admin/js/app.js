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

function toastrAlert(type, success) {
    toastr[type](success);
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
            toastrAlert("success", "Seçilen kaynaklar başarıyla silindi")
        )
        .catch((_) =>
            toastrAlert("error", "Seçilen kaynakları silerken bir sorun oluştu")
        );
};

selectActionDeleteAllSelected = selectActionDecorator(
    selectActionDeleteAllSelected
);

window.selectActionDeleteAllSelected = selectActionDeleteAllSelected;

async function deleteAllUnactivesGlobal(prefix, children, parent_id) {
    let threshold = Array.from(document.querySelectorAll('table input[id$="active-togglebox"]')).some(i => !i.checked)
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
        .then(_ => {
            toastr.success("Aktif olmayan kaynaklar başarıyla silindi!");
            document.querySelectorAll("table tr").forEach((tr) => {
                if (tr.classList.contains("disabled")) tr.remove();
            });
        })
        .catch(_ =>
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
        .then(_ => {
            if (parentNodeName) parent?.remove();
            toastrAlert("success", successMessage);
        })
        .catch(_ => toastrAlert("error", errorMessage));
}

window.tableResourceAction = tableResourceAction;

if (route("categories.create")) {
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
