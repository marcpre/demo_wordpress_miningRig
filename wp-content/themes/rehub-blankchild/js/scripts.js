// 3rd party packages from NPM
import $ from 'jquery';
import dt from 'datatables.net';
import _ from 'lodash';

// Our modules / classes
// import Test from './modules/Test';
import Util from './modules/util';
import Modal from './modules/Modal';
import DataTable from './modules/DataTable'
import RigBuilder from './modules/RigBuilder'
// import MiningRigs from './modules/MiningRigs'
// import HardwareOverview from './modules/HardwareOverview'

// import ComputerHardware from './modules/ComputerHardwareTemplate'

// Instantiate a new object using our modules/classes
// var compHardware = new ComputerHardware()
// var hardwareOverview = new HardwareOverview();
// var miningRigs = new MiningRigs();
var rigBuilder = new RigBuilder();
var dataTable = new DataTable();
// var test = new Test(); // Test Message
var util = new Util();
var modal = new Modal();

