/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

const AUTH_REQUEST_PREFIX = "/dashboard";
window.AUTH_REQUEST_PREFIX = AUTH_REQUEST_PREFIX;

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import { route } from "ziggy-js";
window.route = route;

import $ from "jquery";
window.$ = $;

import toastr from "toastr";
window.toastr = toastr;

import Swal from "sweetalert2";
window.Swal = Swal;

import tippy from "tippy.js"
import 'tippy.js/dist/tippy.css';
window.tippy = tippy;
