"use strict";

import slugify from "slugify";

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

export function transformToSlug(input) {
    return slugify(input.value, {
        strict: false,
        lower: true,
        trim: false,
    }).trim();
}

export function trimGiven(input, char) {
    let val = input.value.replace(new RegExp(char + "$"), "");
    return val.replace(new RegExp("^" + char), "");
}

// CHANGE SLUGGABLE FIELD VALUES TO SLUGS
export function changeSluggableFieldsFormat() {
    document.querySelectorAll("input[sluggable='1']").forEach(function (input) {
        let inputHasDefaultValue = input.value;
        let inputDefaultValueIsNotASlug =
            input.value != transformToSlug(input);

        if (inputHasDefaultValue && inputDefaultValueIsNotASlug) {
            input.value = transformToSlug(input);
        }
        input.addEventListener("input", () => {
            input.value = transformToSlug(input);
        });
        input.addEventListener("blur", () => {
            input.value = trimGiven(input, "-");
        });
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                input.value = trimGiven(input, "-");
            }
        });
    });
}
