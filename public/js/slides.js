var SlideContent = function(params) {
    var currentPosition = params.currentPosition;
    var slideWidth = params.slideWidth;
    var numberOfSlides = params.numberOfSlides;
    var controls = params.controls;
    var slideInner = params.slideInner;
     
     
    this.enableControls = function() {
        controls.click(function(){
            currentPosition = ($(this).attr('id') === 'next') ? currentPosition+1 : currentPosition-1;
            calculateSlidePosition();
             
        });
         
    };
     
    var calculateSlidePosition = function() {
      if (currentPosition === numberOfSlides) {
          currentPosition = 0;
      }
      if (currentPosition < 0) {
          currentPosition = (numberOfSlides - 1);
      }
      animateSlide();
    };
     
    var animateSlide = function() {
        slideInner.animate({
          'marginLeft' : slideWidth*(-currentPosition)
        }, 1000);
    };
     
    calculateSlidePosition();
};