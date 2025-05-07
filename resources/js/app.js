import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import $ from 'jquery';

window.Alpine = Alpine;
window.Swal = Swal;
window.$ = window.jQuery = $;


Alpine.start();
