const TYPE_FIELD = document.getElementById("type");
const TYPES = [
    "text",
    "number",
    "color",
    "range",
    "image",
    "images",
    "video",
    "videos",
    "file",
    "files",
    "multifield",
    "siblingfield",
    "texteditor",
    "select",
];
const SUB_FIELDS = [
    document.querySelector("#prefix"),
    document.querySelector("#suffix"),
    document.querySelector("#placeholder"),
    document.querySelector("#value"),
    document.querySelector("#max_value"),
    document.querySelector("#min_value"),
    document.querySelector("#step"),
];

const SUB_FIELDS_OBJECT = {
    prefix: document.querySelector("#prefix"),
    suffix: document.querySelector("#suffix"),
    placeholder: document.querySelector("#placeholder"),
    value: document.querySelector("#value"),
    max_value: document.querySelector("#max_value"),
    min_value: document.querySelector("#min_value"),
    step: document.querySelector("#step"),
};

const SUB_FEILDS_TREE = {
    text: [
        SUB_FIELDS_OBJECT.prefix,
        SUB_FIELDS_OBJECT.suffix,
        SUB_FIELDS_OBJECT.placeholder,
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.max_value,
    ],
    longtext: [
        SUB_FIELDS_OBJECT.prefix,
        SUB_FIELDS_OBJECT.suffix,
        SUB_FIELDS_OBJECT.placeholder,
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.max_value,
    ],
    number: [
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.max_value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.step,
    ],
    range: [
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.max_value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.step,
    ],
};

function getType() {
    return TYPE_FIELD.value;
}

function takeActionOnTypes() {
    const UPCOMING_FIELDS = getSubFieldsForType(getType());
    resetFields(SUB_FIELDS);
    bootFields(UPCOMING_FIELDS);
}

function resetFields(fields = []) {
    fields.forEach((field) => {
        field.parentElement.style.display = "none";
        field.value = null;
    });
}

function bootFields(fields = []) {
    fields.forEach((field) => (field.parentElement.style.display = "block"));
}

function getSubFieldsForType(type) {
    switch (type.toLowerCase()) {
        case "text":
            return SUB_FEILDS_TREE.text;
            break;
        case "longtext":
            return SUB_FEILDS_TREE.longtext;
            break;
        case "number":
            return SUB_FEILDS_TREE.number;
            break;
        case "range":
            return SUB_FEILDS_TREE.range;
            break;
        default:
            break;
    }
}

TYPE_FIELD.addEventListener("change", takeActionOnTypes);
