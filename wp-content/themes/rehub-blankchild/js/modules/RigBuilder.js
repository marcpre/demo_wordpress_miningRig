import $ from 'jquery';

class RigBuilder {
    constructor() {
        this.events();
        // console.log("RigBuilder Console Log")
    } // end constructor

    events() {
        $(".btn.btn-primary.btn-sm").on("click", this.ourClickDispatcher.bind(this));
    }

    // methods
    ourClickDispatcher(e) {
        var currentButton = $(e.target).closest(".btn.btn-primary.btn-sm");
        console.log("lolonator")

        if (currentButton.data('exists') == 'cpu') {
            console.log("cpu clicked")
        }
        
        if (currentButton.data('exists') == 'motherboard') {
            console.log("motherboard clicked")
        }
        
        if (currentButton.data('exists') == 'graphic-card') {
            console.log("graphic-card clicked")
        }
    }

}

export default RigBuilder;
