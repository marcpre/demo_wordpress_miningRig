// 3rd party packages from NPM
import $ from 'jquery';
import dt from 'datatables.net';

// Our modules / classes
import Test from './modules/Test';
import Util from './modules/util';
import Modal from './modules/Modal';
import DataTable from './modules/datatables.min.js';
import MiningRigSelectionTable from './modules/MiningRigSelectionTable'

// Instantiate a new object using our modules/classes
var util = new Util();
var modal = new Modal();
var datatable = new DataTable();
var miningRigSelectionTable = new MiningRigSelectionTable();
var test = new Test();
