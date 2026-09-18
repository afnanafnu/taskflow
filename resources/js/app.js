import './bootstrap';

import Alpine from 'alpinejs';
import { dataTable } from './components/dataTable';

window.Alpine = Alpine;

window.dataTable = dataTable;

Alpine.start();