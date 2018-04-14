import $ from 'jquery';

class RigBuilder {
    constructor() {
        this.events();
        console.log("RigBuilder Console Log")
    } // end constructor

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    // methods
    ourClickDispatcher(e) {
        var currentLikeBox = $(e.target).closest(".like-box");


        if (currentLikeBox.data('exists') == 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

}

export default RigBuilder;
