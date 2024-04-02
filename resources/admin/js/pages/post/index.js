import * as DH from "../../helpers/document-helpers";

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

document.querySelectorAll("#document-grid .document-item").forEach((item) => {
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
            item.querySelector("header .char-show").textContent = iVal.length;

            if (i.dataset.max_value && iVal >= i.dataset.max_value)
                cs.style.color = "red";
            else cs.style.color = "inherit";
        });
    } else if (t) {
        let tVal = t.value;
        item.querySelector("header .char-show").textContent = tVal.length;
        t?.addEventListener("input", (e) => {
            tVal = e.target.value;
            item.querySelector("header .char-show").textContent = tVal.length;
        });

        if (t.dataset.max_value && tVal >= t.dataset.max_value)
            cs.style.color = "red";
        else cs.style.color = "inherit";
    }
});

// PASTE TITLE VALUE TO SLUG FIELD AS SLUG FORMAT
let titleField = document.getElementById("title");
let slugField = document.getElementById("slug");
titleField.addEventListener("input", function () {
    slugField.value = DH.transformToSlug(this);
});
titleField.addEventListener(
    "blur",
    () => (slugField.value = DH.trimGiven(slugField, "-"))
);
slugField.addEventListener(
    "blur",
    () => (slugField.value = DH.trimGiven(slugField, "-"))
);
