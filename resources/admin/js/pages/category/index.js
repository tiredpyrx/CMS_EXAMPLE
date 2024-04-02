import tippy from "tippy.js";
import * as DH from "../../helpers/document-helpers";

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

// FORCE VİEW FİELD TO BE SLUGGED
let viewField = document.getElementById("view");
viewField.addEventListener(
    "input",
    () => (viewField.value = DH.transformToSlug(viewField))
);
viewField.addEventListener(
    "blur",
    () => (viewField.value = DH.trimGiven(viewField, "-"))
);
