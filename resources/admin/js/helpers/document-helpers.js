import { library, icon } from '@fortawesome/fontawesome-svg-core';
import * as Icons from '@fortawesome/free-solid-svg-icons';

const iconList = Object
  .keys(Icons)
  .filter(key => key !== "fas" && key !== "prefix" )
  .map(icon => Icons[icon])

library.add(...iconList)

export const replaceToIcon = () => {
    const ICON_FALLBACK = document.createElement("i");
    ICON_FALLBACK.className = 'fa fa-pen';
    document.querySelectorAll('.app-icon').forEach(ai => {
        let n = ai.textContent;
        let i = document.createElement('i');
        let _t = icon({prefix: ai.dataset.prefix || 'fas', iconName: n});
        i.className = `${_t?.prefix} fa-${_t?.iconName}`;
        ai.replaceWith(!$(i).height() ? i : ICON_FALLBACK);
    });
};