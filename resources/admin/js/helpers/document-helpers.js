"use strict";

import { library, icon } from "@fortawesome/fontawesome-svg-core";
import * as Icons from "@fortawesome/free-solid-svg-icons";

const iconList = Object.keys(Icons)
    .filter((key) => key !== "fas" && key !== "prefix")
    .map((icon) => Icons[icon]);

library.add(...iconList);

export const replaceToIcon = () => {
    const ICON_FALLBACK = document.createElement("i");
    ICON_FALLBACK.className = "fa fa-pen";
    document.querySelectorAll(".app-icon").forEach((ai) => {
        let n = ai.textContent;
        let i = document.createElement("i");
        let _t = icon({ prefix: ai.dataset.prefix || "fas", iconName: n });
        i.className = `${_t?.prefix} fa-${_t?.iconName}`;
        ai.replaceWith(!$(i).height() ? i : ICON_FALLBACK);
    });
};

export function slideToggle(trigger_id, target_id) {
    $(`#${trigger_id}`).on("click touchstart", () => {
        $(`#${target_id}`).slideToggle();
    });
}

export function fadeToggle({ trigger_class = "", target_class = "" }) {
    if (!trigger_class && !target_class) {
        console.warn(
            "fadeToggle helper expected 2 unpositional argument, got none!"
        );
        return 0;
    }

    $(`.${trigger_class}`).on("click touchstart", (e) => {
        $(e.target).siblings(`.${target_class}`).first().fadeToggle();
    });
}

export function ucfirst(anyText) {
    return anyText[0].toUpperCase() + anyText.substring(1);
}

window.ucfirst = ucfirst;

// // credits https://gist.github.com/4lun/c92affc5ef53cab047dd#file-str-slug-js
// export function slugify(title, separator) {
//     if (typeof separator == "undefined") separator = "-";

//     // Convert all dashes/underscores into separator
//     var flip = separator == "-" ? "_" : "-";
//     title = title.replace(flip, separator);

//     // Remove all characters that are not the separator, letters, numbers, or whitespace.
//     title = title
//         .toLowerCase()
//         .replace(new RegExp("[^a-z0-9" + separator + "\\s]", "g"), "");

//     // Replace all separator characters and whitespace by a single separator
//     title = title.replace(
//         new RegExp("[" + separator + "\\s]+", "g"),
//         separator
//     );

//     return title.replace(
//         new RegExp("^[" + separator + "\\s]+|[" + separator + "\\s]+$", "g"),
//         ""
//     );
// }
